<?php

namespace boss\controllers\payment;
use common\models\finance\FinanceOrderChannel;
use common\models\payment\GeneralPayRefund;
use boss\models\payment\GeneralPaySearch;
use Yii;
use core\models\customer\Customer;
use core\models\customer\CustomerTransRecord;
use boss\models\payment\GeneralPay;
use common\models\payment\GeneralPayLog;
use yii\data\ActiveDataProvider;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GeneralPayController implements the CRUD actions for GeneralPay model.
 */
class GeneralPayController extends Controller
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
     * Lists all GeneralPay models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GeneralPaySearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single GeneralPay model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['view', 'id' => $model->id]);
        } else {
        return $this->render('view', ['model' => $model]);
}
    }

    /**
     * Creates a new GeneralPay model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GeneralPay;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing GeneralPay model.
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
     * Deletes an existing GeneralPay model.
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
     * Finds the GeneralPay model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GeneralPay the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GeneralPay::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * 财务是否对账
     * @param $id   对账ID
     * @param $status   对账状态
     * @return bool
     * @throws NotFoundHttpException
     */
    public function modifyRecontiliation($id , $status)
    {
        $model = $this->findModel($id);
        $model->id = $id;
        $model->is_reconciliation = intval($status);
        return $model->save(false);
    }

    public function actionOrderChannel()
    {
        $channel = FinanceOrderChannel::get_order_channel_list();
        foreach( $channel as $k=>$v ){
            $channel[$k]['text'] = $v['finance_order_channel_name'];
        }
        return json_encode(['results'=>$channel]);
    }

    public function actionTest()
    {
        $data = \core\models\payment\GeneralPay::getPayParams('0.01',1,24,'1500610004',0,['return_url'=>'http://www.baidu.com']);
        dump($data);
        exit;
        $data = \core\models\payment\GeneralPay::getPayParams('0.01',1,23,'1500610004');
        dump($data);
    }

}
