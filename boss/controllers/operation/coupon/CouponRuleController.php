<?php

namespace boss\controllers\operation\coupon;

use Yii;
use dbbase\models\operation\coupon\CouponRule;
use boss\models\operation\coupon\CouponRule as CouponRuleSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Controller;
/**
 * CouponRuleController implements the CRUD actions for CouponRule model.
 */
class CouponRuleController extends Controller
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
     * Lists all CouponRule models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CouponRuleSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,	
        ]);
    }

    /**
     * Displays a single CouponRule model.
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
     * Creates a new CouponRule model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CouponRule;

        if ($model->load(Yii::$app->request->post())) {
        	$dateinfo=Yii::$app->request->post();
        	
           if($dateinfo['CouponRule']['couponrule_classify']==1 && $dateinfo['CouponRule']['couponrule_code_num'] >0){
        		//一码一用
           	$unm=$dateinfo['CouponRule']['couponrule_code_num'];//一码一用数量
           	$name=$dateinfo['CouponRule']['couponrule_Prefix'];//一码一用前缀
           	
          // 	if(\Yii::$app->redis->EXISTS($name)=='0'){
               /***
                * 判断rdeis里面是否有此key值 （虽然数据库做了唯一，但是为了异常，这里再次判断）
           		* $rt=\Yii::$app->redis->SCARD($name);//一共有多少的数量
           		* $rt=\Yii::$app->redis->SPOP($name);//取走并删除 
				***/
           		for($i=1;$i<=$unm;$i++){
           			$datainfo=$name.sprintf("%'.05d\n",$i);
           				\Yii::$app->redis->SADD($name,$datainfo);
           			}
           			
           	/* }elseif(\Yii::$app->redis->EXISTS($name)=='1') {
           	//	判断rdeis里面是否有此key值 ，如果有，跳转回去
           		\Yii::$app->getSession()->setFlash('default','对不起,此前缀的优惠券库中存在！');
           		return $this->redirect(['index']);	
           	} */
		    }
		    
	
 
		    $Couponrule=CouponRuleSearch::couponconfig();
		    $model->couponrule_category_name=$Couponrule[2][$dateinfo['CouponRule']['couponrule_category']];
		    $model->couponrule_type_name=$Couponrule[3][$dateinfo['CouponRule']['couponrule_type']];
		    
		    
		    $model->couponrule_service_type_name='服务类别名称1';
		    $model->couponrule_commodity_name='商品优惠券名称1';
		   
		    
		   
		    $model->couponrule_city_id=  $dateinfo['CouponRule']['city_id'];$model->couponrule_city_name=\core\models\operation\OperationArea::getAreaname($dateinfo['CouponRule']['city_id']);
		    $model->couponrule_promote_type_name=$Couponrule[6][$dateinfo['CouponRule']['couponrule_promote_type']];
		    $model->couponrule_customer_type=implode(',',$dateinfo['CouponRule']['couponrule_customer_type']);
		    
		    foreach ($dateinfo['CouponRule']['couponrule_customer_type'] as $rtyinfo){
		    	$yrtt[]=$Couponrule[5][$rtyinfo];
		    }
		    $model->couponrule_customer_type_name=implode(' ',$yrtt);
		    //记录数据库
		    $model->couponrule_get_start_time=strtotime($dateinfo['CouponRule']['couponrule_get_start_time']);
		    $model->couponrule_get_end_time=strtotime($dateinfo['CouponRule']['couponrule_get_end_time'])+86400;//优惠券的用户可领取结束时间
		    $model->couponrule_use_start_time=strtotime($dateinfo['CouponRule']['couponrule_use_start_time']);
		    $model->couponrule_use_end_time=strtotime($dateinfo['CouponRule']['couponrule_use_end_time'])+86400;//优惠券的用户可使用的结束时间
		    	    
		    $model->is_disabled=0;//是否禁用
		    $model->created_at=time();//创建时间
		    $model->updated_at=time();//更新时间
		    $model->is_del=0;//是否逻辑删除
		    $model->system_user_id=Yii::$app->user->identity->id;//优惠码创建人id
		    $model->system_user_name=Yii::$app->user->identity->username;//优惠码创建人
		    $model->save();
		    
		  // var_dump($model->errors);exit;
            return $this->redirect(['index']);
            
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CouponRule model.
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
     * Deletes an existing CouponRule model.
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
     * Finds the CouponRule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CouponRule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CouponRule::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
