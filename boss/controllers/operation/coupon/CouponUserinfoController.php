<?php

namespace boss\controllers\operation\coupon;

use Yii;
use dbbase\models\operation\coupon\CouponUserinfo;
use boss\models\operation\coupon\CouponUserinfo as CouponUserinfoSearch;
use dbbase\models\operation\coupon\CouponRule;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use core\models\customer\Customer;
use yii\helpers\ArrayHelper;


/**
 * CouponUserinfoController implements the CRUD actions for CouponUserinfo model.
 */
class CouponUserinfoController extends Controller
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
     * Lists all CouponUserinfo models.
     * @return mixed
     */
    public function actionIndex()
    {
    	
    	
    	var_dump( \core\models\operation\coupon\CouponUserinfo::GetCustomerCouponTotal('1','010')); exit;
    	
    	
        $searchModel = new CouponUserinfoSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single CouponUserinfo model.
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
     * Creates a new CouponUserinfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CouponUserinfo;

        if ($model->load(Yii::$app->request->post())) {
        	//批量添加绑定手机号
        	$dateinfo=Yii::$app->request->post();
        	if($dateinfo['CouponUserinfo']['coupon_rule_name_id']=='' && $dateinfo['CouponUserinfo']['coupon_rule_name_id']==0){
        		\Yii::$app->getSession()->setFlash('default','您好,请你选择一个绑定优惠券的规则！');
        		return $this->redirect(['index']);
        	}else{
        	// 如果选择规则，查看这个规则下面是否有优惠券
        	$coupon_rule=CouponRule::find()->where(['id'=>$dateinfo['CouponUserinfo']['coupon_rule_name_id']])->asArray()->one();
			//查看是否还存在优惠前 , 查看时间是否过期，或是已经停用了
			$countcode=\Yii::$app->redis->SCARD($coupon_rule['couponrule_Prefix']);//查询优惠券还剩多少
			
			
			
			
	        	if($countcode >0 && $coupon_rule['couponrule_use_end_time'] > time()  && $coupon_rule['is_disabled']==0){
				// 1 优惠券可以绑定第一步
	        	// 2 此手机号码是否有人
	        	// 3 目前不考虑不同城市优惠的判断，比喻如果这个手机号是天津的，他所选择的规则是北京地区的，理论上是不容许添加的
	        	$dataname=explode('|',$dateinfo['CouponUserinfo']['customer_tel']);
	        	
	        	
	        	foreach ($dataname as $usertel){
	        		$userinfo=Customer::getCustomerInfo($usertel);
	        		
	        		if($userinfo['id']){
	        			//此手机号码是否有人
	        			$model->customer_id=$userinfo['id'];
	        			$model->customer_tel=$usertel;
	        			$model->coupon_userinfo_id=$dateinfo['CouponUserinfo']['coupon_rule_name_id'];
	        			$model->coupon_userinfo_code=\Yii::$app->redis->SPOP($coupon_rule['couponrule_Prefix']);
	        			$model->coupon_userinfo_name=$coupon_rule['couponrule_name'];
	        			$model->coupon_userinfo_price=$coupon_rule['couponrule_price'];
	        			$model->coupon_userinfo_gettime=time();//领取时间
	        			$model->coupon_userinfo_usetime=0;//使用时间
	        			$model->couponrule_use_start_time=$coupon_rule['couponrule_use_start_time'];
	        			$model->couponrule_use_end_time=$coupon_rule['couponrule_use_end_time'];
	        			$model->couponrule_classify=$coupon_rule['couponrule_classify'];
	        			$model->couponrule_category=$coupon_rule['couponrule_category'];
	        			$model->couponrule_type=$coupon_rule['couponrule_type'];
	        			$model->couponrule_service_type_id=$coupon_rule['couponrule_service_type_id'];
	        			$model->couponrule_commodity_id=$coupon_rule['couponrule_commodity_id'];
	        			$model->couponrule_city_limit=$coupon_rule['couponrule_city_limit'];
	        			$model->couponrule_city_id=$coupon_rule['couponrule_city_id'];	
	        			$model->couponrule_customer_type=$coupon_rule['couponrule_customer_type'];
	        			$model->couponrule_use_end_days=$coupon_rule['couponrule_use_end_days'];
	        			$model->couponrule_promote_type=$coupon_rule['couponrule_promote_type'];
	        			$model->couponrule_order_min_price=$coupon_rule['couponrule_order_min_price'];
	        			$model->couponrule_price=$coupon_rule['couponrule_price'];
	        			$model->order_code='0';
	        			$model->is_disabled=$coupon_rule['is_disabled'];
	        			$model->system_user_id=Yii::$app->user->identity->id;
	        			$model->system_user_name=Yii::$app->user->identity->username;;
	        			$model->is_used=0;
	        			$model->created_at=time();
	        			$model->updated_at=time();
	        			$model->is_del=0;
	        			$model->save();
	        		}else{
	        			//此手机号码无人
	        			$model->customer_id=0;
	        			$model->customer_tel=$usertel;
	        			$model->coupon_userinfo_id=1;
	        			$model->coupon_userinfo_code='0';
	        			$model->coupon_userinfo_name='0';
	        			$model->coupon_userinfo_price=0.00;
	        			$model->coupon_userinfo_gettime=time();//领取时间
	        			$model->coupon_userinfo_usetime=0;//使用时间
	        			$model->couponrule_use_start_time=0;
	        			$model->couponrule_use_end_time=0;
	        			$model->couponrule_classify=0;
	        			$model->couponrule_category=0;
	        			$model->couponrule_type=0;
	        			$model->couponrule_service_type_id=0;
	        			$model->couponrule_commodity_id=0;
	        			$model->couponrule_city_limit=0;
	        			$model->couponrule_city_id=0;	
	        			$model->couponrule_customer_type=0;
	        			$model->couponrule_use_end_days=0;
	        			$model->couponrule_promote_type=0;
	        			$model->couponrule_order_min_price=0;
	        			$model->couponrule_price=0;
	        			$model->order_code='0';
	        			$model->is_disabled=0;
	        			$model->system_user_id=Yii::$app->user->identity->id;
	        			$model->system_user_name=Yii::$app->user->identity->username;;
	        			$model->is_used=1;
	        			$model->created_at=time();
	        			$model->updated_at=time();
	        			$model->is_del=0;
	        			$model->save();
	        		}
	        	}	
	        	}else{
	            //此类型的优惠券已经过期或停用或已经领取完了
	        	\Yii::$app->getSession()->setFlash('default','此类型的优惠券已经过期或停用或已经领取完了！');
	        	return $this->redirect(['index']);	
	        	}		
        	}
            return $this->redirect(['index']);
        } else {
        	if(isset($_GET['id'])){
        		$id=$_GET['id'];
        	}else{
        		$id=0;
        	}
        	$ruledatainfo=\core\models\operation\coupon\CouponRule::find()->select('id,couponrule_name')->where(['is_del'=>0,'is_disabled'=>0])->asArray()->all();
        	$ruledata=ArrayHelper::map($ruledatainfo,'id','couponrule_name');
            return $this->render('create', [
                'model' => $model,'id'=>$id,'ruledata'=>$ruledata
            ]);
        }
    }

    
    
    
    /**
     * 批量操作第三方订单
     * @date: 2015-10-9
     * @author: peak pan
     * @return:
     **/
    
    public function actionIndexall()
    {
    	$requestModel = Yii::$app->request->post();
    	if(!empty($requestModel) && array_key_exists('ids',$requestModel)){
    		foreach ($requestModel['ids'] as $iddate){
    			self::findModel($iddate)->delete();
    		}
    	}else{
    		\Yii::$app->getSession()->setFlash('default','请选择需要处理的数据！');
    		return $this->redirect(['index']);
    	}
    
    	return $this->redirect(['index']);
    }
    
    
    
    
    
    
    
    
    
    /**
     * Updates an existing CouponUserinfo model.
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
     * Deletes an existing CouponUserinfo model.
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
     * Finds the CouponUserinfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CouponUserinfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CouponUserinfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
