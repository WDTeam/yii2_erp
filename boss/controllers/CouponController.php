<?php

namespace boss\controllers;

use Yii;

use boss\models\CouponSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use core\models\coupon\Coupon;
use core\models\coupon\CouponCode;

/**
 * CouponController implements the CRUD actions for Coupon model.
 */
class CouponController extends Controller
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
     * Lists all Coupon models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CouponSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Coupon model.
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
     * Creates a new Coupon model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Coupon;
		
        if ($model->load(Yii::$app->request->post()) ) {
			//create coupon logic
			//coupon basic info

			//coupon_type
			switch ($model->coupon_type)
			{
				case 0:
					# code...
					
				break;
				case 1:
					# code...
					//service_types
				break;
				case 2:
					# code...
					//services
				break;
				
			
				default:
					# code...
				break;
			}

			//customer city info
			switch ($model->coupon_city_limit)
			{
				case 0:
					$model->coupon_city_id = 0;
					$model->coupon_city_name = '';
				break;
				case 1:
					
				break;
				
			
				default:
					# code...
				break;
			}

			//coupon customer info
			$customer_types = Coupon::getCustomerTypes();
			$model->coupon_customer_type_name = $customer_types[$model->coupon_customer_type];
		
			//coupon time info
			switch ($mdoel->coupon_time_type)
			{
				case 0:
					$model->coupon_get_end_at = 0;
					$model->coupon_use_end_days = 0;
				break;
				case 1:
					$model->coupon_end_at = 0;
				break;
				
			
				default:
					# code...
				break;
			}
			$model->coupon_time_type_name = $time_types[$model->coupon_time_type];

			//coupon promote type info
			$promote_types = Coupon::getPromoteTypes();
			$model->coupon_promote_type_name = $promote_types[$model->coupon_promote_type];
			switch ($model->coupon_promote_type)
			{
				case 0:
					
				break;
				case 1:
					return $this->redirect(['view', 'id' => $model->id]);
				break;
				case 2:
					return $this->redirect(['view', 'id' => $model->id]);
				break;
			
				default:
					# code...
				break;
			}
		
			//
			
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Coupon model.
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
     * Deletes an existing Coupon model.
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
     * Finds the Coupon model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Coupon the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Coupon::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
