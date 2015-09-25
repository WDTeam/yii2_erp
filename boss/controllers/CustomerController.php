<?php

namespace boss\controllers;

use Yii;
use common\models\Customer;
use boss\models\CustomerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\CustomerAddress;

use common\models\CustomerPlatform;
use common\models\CustomerChannal;

use common\models\OperationCity;
use common\models\GeneralRegion;

use common\models\Order;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Customer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CustomerSearch;

        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * 加入黑名单
     */
    public function actionAddToBlock($id)
    {
        $model = $this->findModel($id);
        $model->is_del = 1;
        $model->save();
        return $this->redirect(['/customer/block?CustomerSearch[is_del]=1']);
    }

    /**
     * 从黑名单中取消
     */
    public function actionRemoveFromBlock($id)
    {
        $model = $this->findModel($id);
        $model->is_del = 0;
        $model->save();
        return $this->redirect(['/customer/index?CustomerSearch[is_del]=0']);
    }

    /**
     * Lists all Customer models.
     * @return mixed
     */
    public function actionBlock()
    {
        $searchModel = new CustomerSearch;
        var_dump(Yii::$app->request->getQueryParams());
        
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('block', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Customer model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        //组装model
        $operationCity = OperationCity::find()->where([
            'id'=>$model->operation_city_id
            ])->one();

        $customerPlatform = CustomerPlatform::find()->where([
            'id'=>$model->platform_id
            ])->one();
        $customerChannal = CustomerChannal::find()->where([
            'id'=>$model->channal_id
            ])->one();

        $generalRegion = GeneralRegion::find()->where([
            'id'=>$model->general_region_id
            ])->one();

        //订单地址
        $address_count = CustomerAddress::find()->where([
            'customer_id'=>$model->id,
            ])->count();
        $customerAddress = CustomerAddress::find()->where([
            'customer_id'=>$model->id,
            'customer_address_status'=>1])->one();
        $general_region_id = $customerAddress->general_region_id;
        $general_region = GeneralRegion::find()->where([
            'id'=>$general_region_id,
            ])->one();
        if ($address_count <= 0) {
            $order_addresses = '-';
        }
        if ($address_count == 1) {
            $order_addresses =  $general_region->general_region_province_name 
            . $general_region->general_region_city_name 
            . $general_region->general_region_area_name
            . $customerAddress->customer_address_detail;
        }
        if ($address_count > 1) {
            $order_addresses = $general_region->general_region_province_name 
            . $general_region->general_region_city_name 
            . $general_region->general_region_area_name
            . $customerAddress->customer_address_detail
            . '...';
        }

        //订单数量
        $order_count = Order::find()->where([
            'customer_id'=>$model->id
            ])->count();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('view', ['model' => $model, 
                'operationCity'=>$operationCity, 
                'customerPlatform'=>$customerPlatform, 
                'customerChannal'=>$customerChannal,
                'generalRegion'=>$generalRegion,
                'order_addresses'=>$order_addresses,
                'order_count'=>$order_count,
                ]);
        }
    }

    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Customer;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Customer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
