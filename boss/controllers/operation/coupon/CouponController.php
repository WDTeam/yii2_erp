<?php

namespace boss\controllers\operation\coupon;

use Yii;

use boss\models\operation\coupon\CouponSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use core\models\operation\coupon\Coupon;
use core\models\operation\coupon\CouponCode;

use \core\models\operation\OperationCity;

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
        if ($model->load(Yii::$app->request->post())) {
			
			
			//coupon basic info
			
			//coupon categories
			$coupon_categories = Coupon::getCategories();
			$model->coupon_category_name = $coupon_categories[$model->coupon_category];

			//coupon type
			$service_types = Coupon::getServiceTypes();
			$model->coupon_type_name = $service_types[$model->coupon_type];
			switch ($model->coupon_type)
			{
				case 0:
					$model->coupon_service_type_id = 0;
					$model->coupon_service_id = 0;
				break;
				case 1:
					$model->coupon_service_id = 0;
				break;
				case 2:
				
				break;
			
		
				default:
					# code...
				break;
			}
		
		
			//coupon city
			$city_types = Coupon::getCityTypes();
			$cityOnlineList = OperationCity::getCityOnlineInfoList();
			$cities = array();
			if(!empty($cityOnlineList)){
				foreach($cityOnlineList as $value){
					$cities[$value['city_id']] = $value['city_name'];
				}
			}
			switch ($model->coupon_city_limit)
			{
				case 0:
					$model->coupon_city_id = 0;
				break;
		
				case 1:
					$model->coupon_city_name = $cities[$model->coupon_city_id];
				
				break;
		
		
				default:
					# code...
				break;
			}

			//customer type 
			$customer_types = Coupon::getCustomerTypes();
			$model->coupon_customer_type_name = $customer_types[$model->coupon_customer_type];

			//coupon time type
			$time_types = Coupon::getTimeTypes();
			$model->coupon_time_type_name = $time_types[$model->coupon_time_type];
			switch ($model->coupon_time_type)
			{
				case 0:
					$model->coupon_begin_at = strtotime($_POST['Coupon']['coupon_begin_at']);
					$model->coupon_end_at = strtotime($_POST['Coupon']['coupon_end_at']);
					$model->coupon_get_end_at = 0;
					$model->coupon_use_end_days = 0;
				break;
				case 1:
		            
					$model->coupon_begin_at = strtotime($_POST['Coupon']['coupon_begin_at']);
					$model->coupon_end_at = 0;
					$model->coupon_get_end_at = strtotime($_POST['Coupon']['coupon_get_end_at']);
				
				break;
					
		
				default:
					# code...
				break;
			}
		
			//coupon_promote_type
			$promote_types = Coupon::getPromoteTypes();
			$model->coupon_promote_type_name = $promote_types[$model->coupon_promote_type];
			switch ($model->coupon_promote_type)
			{
				case 0:
					$model->coupon_order_min_price = 0;
				break;
		
				case 1:
				break;
		
				default:
					# code...
				break;
			}


			//coupon other infos
			$model->is_disabled = 0;
			$model->created_at = time();		
			$model->updated_at = 0;
			$model->is_del = 0;
		
			//coupon system user
			$model->system_user_id = 0;
			$model->system_user_name = '';
			$model->validate();
			if($model->hasErrors()){
				var_dump($model->getErrors());
				return $this->render('create', ['model' => $model]);
			}
		    $model->save();

		    //insert into coupon code
			
			//var_dump($_POST['Coupon']['coupon_code_num']);
			//exit();
		    for($i=0; $i<$_POST['Coupon']['coupon_code_num']; $i++){
				$couponCode = new CouponCode;
				$couponCode->coupon_id = $model->id;
				$couponCode->coupon_name = $model->coupon_name;
				$couponCode->coupon_price = $model->coupon_price;
				$couponCode->created_at = time();
				$couponCode->updated_at = 0;
				$couponCode->is_del = 0;
		        $coupon_code_str = CouponCode::generateCouponCode();
		        $couponCodeTemp =  CouponCode::find()->where(['coupon_code'=>$coupon_code_str])->one();
		        while($couponCodeTemp){
		            
		             $coupon_code_str = CouponCode::generateCouponCode();
		             $couponCodeTemp =  CouponCode::find()->where(['coupon_code'=>$coupon_code_str])->one();
		        }
		        $couponCode->coupon_code = $coupon_code_str;
				$couponCode->validate();
				if($couponCode->hasErrors()){
					return $this->render('create', ['model' => $model]);
				}
		        $couponCode->save(); 
		    }
			return $this->redirect(['view', 'id' => $model->id]);
		}else{
			return $this->render('create', ['model' => $model]);
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
    public function actionTest(){
        $customerCoupon = \core\models\operation\coupon\CouponCustomer::listCustomerCoupon('18500041311');
        var_dump($customerCoupon);
    }
    
    public function actionBind($id)
    {
        $model = $this->findModel($id);
        return $this->renderAjax('_bind',[
            'model'=>$model,
        ]);
    }
}
