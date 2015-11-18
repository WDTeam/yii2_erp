<?php
/**
* 控制器 优惠券规则
* ==========================
* 北京一家洁 版权所有 2015-2018 
* ----------------------------
* 这不是一个自由软件，未经授权不许任何使用和传播。
* ==========================
* @date: 2015-11-12
* @author: peak pan 
* @version:1.0
*/

namespace boss\controllers\operation\coupon;

use Yii;
use dbbase\models\operation\coupon\CouponRule;
use boss\models\operation\coupon\CouponRule as CouponRuleSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Controller;
use PHPExcel;
use PHPExcel_IOFactory;

use	dbbase\models\operation\CouponUserinfoceshi;
use	dbbase\models\operation\coupon\CouponUserinfo;
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

    
    
    public function actionIndexceshi()
    {
    	ini_set('max_execution_time',864000);
       /* $userinfoceshi= new CouponUserinfoceshi;
       $datainfo=$userinfoceshi->find()
       ->where(['and','city_id is null'])
       ->asArray()
       ->limit(50)
       ->all();
    	
      foreach ($datainfo as $key=>$vlu){
      $cityid=\core\models\operation\OperationArea::getAreaid($vlu['city_name']);
      $saveinfo=$userinfoceshi->findOne($vlu['id']);
      $saveinfo->city_id=$cityid;
      $saveinfo->save();
      echo  $key;
      } */
      
      // SELECT * from ejj_coupon_userinfoceshi  group by order_type order by id desc
     /*   $userinfoceshi= new CouponUserinfoceshi;
       $datainfo=$userinfoceshi->find()
       ->groupBy('order_type')
       ->limit(1)
       ->asArray()
       ->all(); */
		  /*  $googsdata=['通用'=>0,'洗衣'=>23,'洗鞋'=>25,'空调清洗'=>10,'杀虫'=>33,'地板抛光打蜡'=>19,'石材结晶保养'=>22,'地毯保养'=>18,'饮水机清洗'=>14,'擦玻璃'=>5,'厨房高温保洁'=>3,'卫生间保洁'=>4,'洗衣机清洗'=>15,'油烟机清洗'=>9,'窗帘清洗'=>34,'家庭保洁'=>1]; 
		     $rty='';
		    	foreach ($datainfo as $typedata){
		    		$saveinfo=$userinfoceshi->find()->where(['order_type'=>$typedata['order_type']])->one();
					echo "UPDATE ejj_coupon_userinfoceshi SET order_typeid='".$googsdata[$typedata['order_type']]."' where order_type='".$typedata['order_type']."';<br>";
		    	} */
		//导入到新表       
       $userinfoceshi= new CouponUserinfoceshi;
       
       $data=$userinfoceshi->find()
       ->where(['coupon_userinfo_id'=>2])
       ->limit(5)
       ->asArray()
       ->all();
    	foreach ($data as $newdata){
    		$newcoupon=new CouponUserinfo;
    			//领取的优惠券
				if($newdata['city_id']==0){
					//全网优惠券
					$newcoupon->couponrule_city_limit=1;
					$newcoupon->couponrule_city_id=0;
				}else{
					//地区优惠券
					$newcoupon->couponrule_city_limit=2;
					$newcoupon->couponrule_city_id=$newdata['city_id'];					
				}
				if($newdata['order_typeid']==0){
				//全国通用优惠券
					$newcoupon->couponrule_type=1;
					$newcoupon->couponrule_service_type_id=0;
					$newcoupon->couponrule_commodity_id=0;
				}else{
					//不是全网优惠券 对应老数据就是商品优惠券，老数据没有，类别优惠券
					$newcoupon->couponrule_type=3;
					$newcoupon->couponrule_service_type_id=0;
					$newcoupon->couponrule_commodity_id=$newdata['order_typeid'];
				}
    		$newcoupon->coupon_userinfo_code=$newdata['coupon_userinfo_code']?$newdata['coupon_userinfo_code']:'0';
    		$newcoupon->coupon_userinfo_name=$newdata['coupon_userinfo_name']?$newdata['coupon_userinfo_name']:'优惠券';
    		$newcoupon->coupon_userinfo_gettime=$newdata['coupon_userinfo_gettime'];//领取时间默认为开始时间
    		$newcoupon->couponrule_use_start_time=$newdata['coupon_userinfo_gettime'];
    		$newcoupon->couponrule_use_end_time=$newdata['couponrule_use_end_time'];
    		$newcoupon->coupon_userinfo_price=$newdata['coupon_userinfo_price'];
    		$newcoupon->customer_tel=$newdata['customer_tel'];
    		///////////////////////////////////////////
    		$newcoupon->couponrule_order_min_price=0;
    		$newcoupon->customer_id=0;
    		$newcoupon->coupon_userinfo_id=0;
    		$newcoupon->coupon_userinfo_usetime=0;
    		$newcoupon->couponrule_classify=1;
    		$newcoupon->couponrule_category=1;
    		$newcoupon->couponrule_customer_type=1;
    		$newcoupon->couponrule_use_end_days=0;
    		$newcoupon->couponrule_promote_type=1;
    		$newcoupon->couponrule_price=50;
    		$newcoupon->order_code='0';
    		$newcoupon->is_disabled=0;
    		$newcoupon->system_user_id=0;
    		$newcoupon->system_user_name='老数据导入';
    		$newcoupon->is_used=0;
    		$newcoupon->created_at=time();
    		$newcoupon->updated_at=time();
    		$newcoupon->is_del=0;
    		$islcok=$newcoupon->save();
    		
    		var_dump($islcok);exit;
    		if($islcok){
    			$userinfoceshi->findOne($newdata['id'])->delete(); 
    		}else{
    		//失败记录日志
    			file_put_contents('log.txt',json_encode($newcoupon)."\n",FILE_APPEND);
    		}
    		unset($newcoupon);
    	}
    	var_dump('11');exit;	
    }
    
    /**
     * Lists all CouponRule models.
     * @return mixed
     */
    public function actionIndex()
    {
    	//退款退优惠券18964831206
    	//$rty=\core\models\operation\coupon\CouponUserinfo::GetCustomerDueCouponList('13501268242','1');
      	//var_dump($rty);exit;
    	//$coupon_code,$customer_tel,$couponrule_service_type_id,$couponrule_commodity_id,$city_id){
    	//优惠码检测使用
    	//$rty=\core\models\operation\coupon\CouponRule::get_is_coupon_status('lsq00003','18001305711',1,0,1);
    	//var_dump($rty);exit;
    	   
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
     * ajax验证 优惠券是否唯一
     * @return array
     */
    public function actionAjaxInfo(){
    	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	   $CouponRuleModel = new CouponRule;
    		$CouponRuleModel->load(Yii::$app->request->post());
    		return \yii\bootstrap\ActiveForm::validate($CouponRuleModel,['couponrule_Prefix']);
    	
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
           	$name=strtolower($dateinfo['CouponRule']['couponrule_Prefix']);//一码一用前缀

           	if(\Yii::$app->redis->EXISTS($name)=='0'){
               /***
                * 判断rdeis里面是否有此key值 （虽然数据库做了唯一，但是为了异常，这里再次判断）
           		* $rt=\Yii::$app->redis->SCARD($name);//一共有多少的数量
           		* $rt=\Yii::$app->redis->SPOP($name);//取走并删除 
           		* $rt=\Yii::$app->redis->SMEMBERS($name) //随机取出一个
				***/
           		for($i=1;$i<=$unm;$i++){
           			$datainfo=$name.sprintf("%'.05d\n",$i);
           				\Yii::$app->redis->SADD($name,trim($datainfo));
           			}
           			
           	 }elseif(\Yii::$app->redis->EXISTS($name)=='1') {
           	//	判断rdeis里面是否有此key值 ，如果有，跳转回去
           		\Yii::$app->getSession()->setFlash('default','对不起,此前缀的优惠券库中存在！');
           		return $this->redirect(['index']);	
           	}
		    }
		    $Couponrule=CouponRuleSearch::couponconfig(); 
		    $model->couponrule_category_name=$Couponrule[2][$dateinfo['CouponRule']['couponrule_category']];
		    $model->couponrule_type_name=$Couponrule[3][$dateinfo['CouponRule']['couponrule_type']];
		    if($dateinfo['CouponRule']['couponrule_classify']=='2'){
		    $model->couponrule_price_sum=$dateinfo['CouponRule']['couponrule_price']*$dateinfo['CouponRule']['couponrule_code_max_customer_num'];
		    }else{
		    $model->couponrule_price_sum=$dateinfo['CouponRule']['couponrule_price']*$dateinfo['CouponRule']['couponrule_code_num']; 
		    }
		    
		 
		    
		    if($dateinfo['CouponRule']['couponrule_type']==1){
		    //全网优惠券	
		    	$model->couponrule_type_name='全网优惠券';
		    	$model->couponrule_service_type_id=0;
		    	$model->couponrule_service_type_name='0';
		    	$model->couponrule_commodity_id=0;
		    	$model->couponrule_commodity_name='0';
		    	
		    	
		    }elseif ($dateinfo['CouponRule']['couponrule_type']==2){
		    // 类别券	
		    	$model->couponrule_type_name='类别优惠券';
		    	$model->couponrule_service_type_id=$dateinfo['CouponRule']['couponrule_service_type_id'];
		    	$data_info_name=\core\models\operation\OperationCategory::getAllCategory();
		    	$data_es_name=\yii\helpers\ArrayHelper::map($data_info_name, 'id', 'operation_category_name');
		    	$model->couponrule_service_type_name=$data_es_name[$dateinfo['CouponRule']['couponrule_service_type_id']];
		    	$model->couponrule_commodity_id=0;
		    	$model->couponrule_commodity_name='0';
		    }else{
		    // 商品券	
		    	$model->couponrule_type_name='商品优惠券';
		    	$model->couponrule_service_type_id=0;
		    	$model->couponrule_service_type_name='0';
		    	$model->couponrule_commodity_id=$dateinfo['CouponRule']['couponrule_commodity_id'];
		        $goods_data=\core\models\operation\OperationGoods::getAllCategory_goods();
		    	$model->couponrule_commodity_name=$goods_data[$dateinfo['CouponRule']['couponrule_commodity_id']];	
		    }
		    
		    
		    $model->couponrule_Prefix=  $name;
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

    
    

    public function actionExport()
    {
    	if(isset($_GET['id'])){
    		$datainfo = CouponRule::find()->select('id,couponrule_type_name,couponrule_service_type_name,couponrule_commodity_name,couponrule_name,couponrule_use_start_time,couponrule_use_end_time,couponrule_classify,couponrule_price,couponrule_Prefix')->where(['id'=>$_GET['id']])->asArray()->one();
    	}else{
    		\Yii::$app->getSession()->setFlash('default','灰常抱歉,您传入的值不存在！');
    		return $this->redirect(['index']);
    	}
    	$data=\Yii::$app->redis->SMEMBERS($datainfo['couponrule_Prefix']);
    	
    	
    	if(count($data)=='0'){
    		\Yii::$app->getSession()->setFlash('default','此规则下木有优惠券了！');
    		return $this->redirect(['index']);
    	}
    	
    	
    	
    	$objPHPExcel = new PHPExcel();
    	ob_start();
    	$objPHPExcel->getProperties()->setCreator('ejiajie')
    	->setLastModifiedBy('ejiajie')
    	->setTitle('Office 2007 XLSX Document')
    	->setSubject('Office 2007 XLSX Document')
    	->setDescription('Document for Office 2007 XLSX, generated using PHP classes.')
    	->setKeywords('office 2007 openxml php')
    	->setCategory('Result file');
    	$objPHPExcel->setActiveSheetIndex(0)
    	->setCellValue('A1', '优惠码')
    	->setCellValue('B1', '优惠名称')
    	->setCellValue('C1', '可用开始时间')
        ->setCellValue('D1', '可用结束时间')
        ->setCellValue('E1', '最小金额')
    	 ->setCellValue('F1', '优惠券类型名称')
    	 ->setCellValue('G1', '服务类别名称')
    	 ->setCellValue('H1', '如果是商品名称');
    	$i = 2;
    	foreach ($data as $k => $v) {
    		$objPHPExcel->setActiveSheetIndex(0)
    		->setCellValue('A' . $i, $v)
    		->setCellValue('B' . $i, $datainfo['couponrule_name'])
    		->setCellValue('C' . $i, date('Y-m-d H:i:s', $datainfo['couponrule_use_start_time']))
            ->setCellValue('D' . $i, date('Y-m-d H:i:s', $datainfo['couponrule_use_end_time']))
            ->setCellValue('E' . $i, $datainfo['couponrule_price'])
    		->setCellValue('F' . $i, $datainfo['couponrule_type_name'])
    		->setCellValue('G' . $i, $datainfo['couponrule_service_type_name'])
    		->setCellValue('H' . $i, $datainfo['couponrule_commodity_name']);
    		$i++;
    	}
    	$objPHPExcel->getActiveSheet()->setTitle('优惠券');
    	$objPHPExcel->setActiveSheetIndex(0);
    	$filename = urlencode('优惠券数据导出-'.$datainfo['couponrule_name']) . '_' . date('Y-m-dHis');
    	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    	ob_end_clean();
    	header('Content-Type: application/vnd.ms-excel');
    	header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
    	header('Cache-Control: max-age=0');
    	$objWriter->save('php://output');
    	exit;
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
