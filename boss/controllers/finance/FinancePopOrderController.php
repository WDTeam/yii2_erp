<?php
/**
* 对账控制器
* ==========================
* 北京一家洁 版权所有 2015-2018 
* --------------------------
* 这不是一个自由软件，未经授权不许任何使用和传播。
* ==========================
* @date: 2015-9-23
* @author: peak pan 
* @version:1.0
*/

namespace boss\controllers\finance;

use Yii;
use dbbase\models\finance\FinancePopOrder;
use boss\models\finance\FinancePopOrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use dbbase\models\finance\FinanceOrderChannel;
use dbbase\models\finance\FinanceHeader;
use boss\models\finance\FinanceRecordLogSearch;
use boss\models\finance\FinanceOrderChannelSearch;
use core\models\order\OrderSearch;
use core\models\payment\PaymentSearch;
use dbbase\models\finance\FinanceRecordLog;
use crazyfd\qiniu\Qiniu;
use dbbase\models\finance\FinancePayChannel;
use core\models\order\Order;

/**
 * FinancePopOrderController implements the CRUD actions for FinancePopOrder model.
 */
class FinancePopOrderController extends Controller
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
   * redis 测试方法   仅适用于潘高峰个人使用   
   * @date: 2015-11-4
   * @author: peak pan
   * @return:
   **/ 
    
    public function actionIndexredis()
    {
    	
    	echo sprintf("%'.06d\n", 1);  exit;
    	
    
    	
    	
    	
    	
    	//\Yii::$app->redis->SADD($name,$datainfo);
    	
    	//exit;
   // $rty=\Yii::$app->redis->EXISTS($name);
    	//var_dump($rty);exit;
    	
    	//添加
    	//\Yii::$app->redis->SADD($name,$datainfo);
    	//一共有多少的数量
    	//$rt=\Yii::$app->redis->SCARD($name);
    	//取走并删除
    	//$rt=\Yii::$app->redis->SPOP($name);
    	//var_dump($rt);
    	//exit;
    	//for($i=1;$i<20000;$i++){
    	//	$datainfo='1000000'.$i;
    		//\Yii::$app->redis->zadd($name,$datainfo,$i);
    		//添加
    	//	\Yii::$app->redis->SADD($name,$datainfo);	
    		//echo  $i; 
    	//}
    	
    	//echo  'ok'; exit;
    	
    }
   
    /**
    * 对账方法
    * @date: 2015-9-23
    * @author: peak pan
    * @return:
    **/
    public function actionIndex()
    {
    	$model = new FinancePopOrderSearch;
    	$modelinfo= new FinancePopOrder;
    	
    	if(Yii::$app->request->isPost) {
    		if(true){
    		//开启七牛上传，文件存储在七牛Yii::$app->params['uploadpath']
    			$data = \Yii::$app->request->post();
    			$model->load($data);
    			$file = UploadedFile::getInstance($model, 'finance_uplod_url');
    			
    			if(!isset($file->baseName)){
    				\Yii::$app->getSession()->setFlash('default','请上传对账单！');
    				return $this->redirect(['index']);
    			}
    			
    			if($data['FinancePopOrderSearch']['finance_order_channel_id']=='' && $data['FinancePopOrderSearch']['finance_pay_channel_id']==''){
    				\Yii::$app->getSession()->setFlash('default','渠道至少选择一个！');
    				return $this->redirect(['index']);
    			}
    			
    			$filenamesitename=$file->baseName;
    			if($file){
    				$qiniu = new Qiniu();
    				$path = $qiniu->uploadFile($file->tempName);
    				$model->finance_uplod_url = $path['key'];
    			}
    			$qiniuurl=$qiniu->getLink($path['key']);
    			$filePath=$file->tempName;
    		}else{
    			//文件存储在本地
    			$model->finance_uplod_url = UploadedFile::getInstance($model, 'finance_uplod_url');
    			if ($model->finance_uplod_url && $model->validate()) {
    				$path='upload/';
    				if(!file_exists($path))mkdir($path);
    				$filenamesitename=$model->finance_uplod_url->baseName;
    				$filename=time().'.'.$model->finance_uplod_url->extension;
    				$qiniuurl='0';
    				if(!$model->finance_uplod_url->saveAs($path.$filename)){
    					return ["result"=>"Fail"];
    				}
    			}
    			$filePath = $path.$filename;
    		}
    
    		$objPHPExcel = \PHPExcel_IOFactory::load($filePath);
    		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
    	    		
    		header("Content-Type: text/html; charset=utf-8");
    		$post = Yii::$app->request->post();
    		
    		//通过渠道查询对应的表头信息
    		$order_channel = new FinanceHeader;
    		if($post['FinancePopOrderSearch']['finance_order_channel_id']){
    			$channelid=$post['FinancePopOrderSearch']['finance_order_channel_id'];
    			$alinfo=FinanceHeader::find()
    			->select('finance_header_where,finance_header_key')
    			->andWhere(['!=','finance_header_where','0'])
    			->andWhere(['=','finance_order_channel_id',$channelid])
    			->asArray()->All();
    			$paychannelid='0';
    		}else{
    			$paychannelid=$post['FinancePopOrderSearch']['finance_pay_channel_id'];
    			$alinfo=FinanceHeader::find()
    			->select('finance_header_where,finance_header_key')
    			->andWhere(['!=','finance_header_where','0'])
    			->andWhere(['=','finance_pay_channel_id',$paychannelid])
    			->asArray()->All();
    			$channelid='0';
    		}
    		
    		$n=1;
    		$statusinfo=$model->id_header(array_filter($sheetData[1]),$channelid,$paychannelid);
    		//验证上传的表头和选择的是否一致
    		if($statusinfo){
    			\Yii::$app->getSession()->setFlash('default','对不起你上传的表不对！'); 
    		  return $this->redirect(['index']);
    		}else {
    		//向记录表里面插入一条空数据	
    		$FinanceRecordLog = new FinanceRecordLogSearch;
    		$FinanceRecordLog->insert();
    		$lastidRecordLog=$FinanceRecordLog->id;
    		}
    		
    		
    		if($lastidRecordLog==0){
    		  \Yii::$app->getSession()->setFlash('default','账期出现问题，请重新上传！'); 
    		  return $this->redirect(['index']);
    		 }
    		 
    		 $FinanceRecordLog = new FinanceRecordLogSearch;
    		 
    		 $customer_info = FinanceRecordLog::find()->where(['finance_order_channel_name'=>trim($filenamesitename)])->count();
    		 if($customer_info>0){
    		 	\Yii::$app->getSession()->setFlash('default','对不起，此账期已经上传过！');
    		 	return $this->redirect(['index']);
    		 }
    		 
    		foreach ($sheetData as $key=>$value){
    			//去除表头
    			if($n>1 && !empty($value['A'])){
    			$statusinfo=$model->PopOrderstatus($alinfo,$value,$channelid,$paychannelid);
    			//var_dump($statusinfo);exit;
    			
    			$postdate['order_code'] =$statusinfo['order_code']; //系统订单号
    			$postdate['order_status_name'] =$statusinfo['order_status_name']?$statusinfo['order_status_name']:'未知';  //订单状态
    			$postdate['order_money'] =$statusinfo['order_money'];// 订单金额
    			$postdate['finance_status'] =1;// 收款状态 1 未确定 2已确定

    			$postdate['finance_record_log_id'] =$lastidRecordLog;
    			$postdate['finance_pop_order_number'] =$statusinfo['order_channel_order_num'];
    			$postdate['finance_order_channel_id'] =$channelid;
    			$postdate['finance_order_channel_title'] =FinanceOrderChannel::getOrderChannelByName($channelid);
    			$postdate['finance_pay_channel_id'] =$paychannelid=='0'?$statusinfo['pay_channel_id']:$paychannelid;
    			$postdate['finance_pay_channel_title'] =$paychannelid=='0'?$statusinfo['order_pay_channel_name']:FinancePayChannel::getPayChannelByName($postdate['finance_pay_channel_id']);
    			$postdate['finance_pop_order_customer_tel'] =$statusinfo['order_customer_phone'];
    			$postdate['finance_pop_order_worker_uid'] =$statusinfo['worker_id'];
    			$postdate['finance_pop_order_booked_time'] =$statusinfo['order_booked_begin_time'];
    			$postdate['finance_pop_order_booked_counttime'] =$statusinfo['order_booked_end_time'];// 按照分钟计算 
    			$postdate['finance_pop_order_sum_money'] =$statusinfo['finance_pop_order_sum_money']; //对账金额
    			//优惠卷金额
    			$postdate['finance_pop_order_coupon_count'] =0; 
    			//优惠卷id
    			$postdate['finance_pop_order_coupon_id'] =$statusinfo['coupon_id'];  
    			$postdate['finance_pop_order_order2'] =$statusinfo['order_code'];
    			//获取渠道唯一订单号有问题需要问问
    			$postdate['finance_pop_order_channel_order'] =$statusinfo['order_channel_order_num'];
    			//$post['FinancePopOrder']['finance_pop_order_order_type'] =$statusinfo['order_service_type_id'];
    			$postdate['finance_pop_order_order_type'] =$statusinfo['order_service_type_id'];
    			$postdate['finance_pop_order_status'] =$statusinfo['order_before_status_dict_id'];
    			$postdate['finance_pop_order_finance_isok'] =0;
    			//折扣总金额
    			$postdate['finance_pop_order_discount_pay'] =$statusinfo['order_use_coupon_money'];
    			$postdate['finance_pop_order_reality_pay'] =$statusinfo['finance_pop_order_reality_pay'];
    			$postdate['finance_pop_order_order_time'] =$statusinfo['created_at'];
    			$postdate['finance_pop_order_pay_time'] =$statusinfo['created_at'];
    			$postdate['finance_pop_order_pay_status'] =0;//财务确定处理按钮状态
    			$postdate['finance_pop_order_pay_status_type'] =$statusinfo['finance_pop_order_pay_status_type'];
    			$postdate['finance_pop_order_pay_title'] = $filenamesitename;
    			$postdate['finance_pop_order_check_id'] = Yii::$app->user->id;
    			
    			$postdate['finance_pop_order_finance_time'] =time();//财务对账提交时间
    			
    			$postdate['finance_order_channel_statuspayment'] =strtotime( $post['FinancePopOrderSearch']['finance_order_channel_statuspayment']);
    			$postdate['finance_order_channel_endpayment'] =strtotime($post['FinancePopOrderSearch']['finance_order_channel_endpayment']);
    			$postdate['create_time'] = time();
    			$postdate['is_del'] =0;
    		
    			
    			
    			$_model = clone $model;
    			$_model->setAttributes($postdate);
    			$_model->save();
    			unset($postdate);
    		}
    		$n++;
    		}
    	 	//$FinanceRecordLog = new FinanceRecordLogSearch;
    		//获取渠道唯一订单号有问题需要问问
    	 	$customer_info = FinanceRecordLog::findOne($lastidRecordLog);
    		$customer_info->finance_order_channel_id =1;
    		//对账名称
    		$customer_info->finance_order_channel_name =$filenamesitename;
    		//收款渠道id
    		$customer_info->finance_pay_channel_id=$channelid;
    		
    		//$modelPay = new FinancePayChannelSearch;
    		$modelesr= new FinanceOrderChannelSearch;
    		$ordername=$modelesr->searchfind(array('id'=>$channelid),'finance_order_channel_name');
    		
    		
    		//收款渠道名称
    		$customer_info->finance_pay_channel_name=$ordername;
    		//成功记录数
    		$customer_info->finance_record_log_succeed_count =$modelinfo::find()->andWhere(['finance_record_log_id' => $lastidRecordLog])->andWhere(['finance_pop_order_pay_status_type' => '1'])->count('id'); 
    		$sumt=$modelinfo::find()->select(['sum(finance_pop_order_sum_money) as sumoney'])
    		->andWhere(['finance_record_log_id' => $lastidRecordLog])
    		->andWhere(['finance_pop_order_pay_status_type' => '1'])->asArray()->all();
    		$customer_info->finance_record_log_succeed_sum_money =$sumt[0]['sumoney'];
    		//人工确认笔数
    		$customer_info->finance_record_log_manual_count =0;
    		//人工确认金额
    		$customer_info->finance_record_log_manual_sum_money =0;
    		//失败笔数
    		$customer_info->finance_record_log_failure_count=$modelinfo::find()
    		->andWhere(['finance_record_log_id' => $lastidRecordLog])
    		->andWhere(['finance_pop_order_pay_status_type' => '4'])->count('id'); 
    		
    		//失败总金额
    		$sumterr=$modelinfo::find()->select(['sum(finance_pop_order_sum_money) as sumoneyinfo'])
    		->andWhere(['finance_record_log_id' => $lastidRecordLog])
    		->andWhere(['finance_pop_order_pay_status_type' => '4'])->asArray()->one();
    		
    		$customer_info->finance_record_log_failure_money =$sumterr['sumoneyinfo']?$sumterr['sumoneyinfo']:0;
    		//对账人
    		$customer_info->finance_record_log_confirm_name =Yii::$app->user->identity->username;
    		//获取服务费
    		$customer_info->finance_record_log_fee =FinancePopOrderSearch::get_fee_pay($channelid,$paychannelid,$lastidRecordLog);
    		$customer_info->finance_record_log_statime =strtotime($post['FinancePopOrderSearch']['finance_order_channel_statuspayment']);
    		$customer_info->finance_record_log_endtime =strtotime($post['FinancePopOrderSearch']['finance_order_channel_endpayment']);
    		$customer_info->finance_record_log_qiniuurl =$qiniuurl;
    		$customer_info->create_time= time();
    		$customer_info->is_del=0;
    		$customer_info->save();
    		return $this->redirect(['index','id' =>$lastidRecordLog]);
    	}

  		##########################
  		//获取下单渠道
 
        $tyu=\core\models\operation\OperationOrderChannel::getorderchannellist('all');
         //获取支付渠道
        $paydat=\core\models\operation\OperationPayChannel::getpaychannellist('all');
         
         $searchModel = new FinancePopOrderSearch;
         //默认条件
         $searchModel->load(Yii::$app->request->getQueryParams());
         $searchModel->is_del=0;
         $searchModel->finance_pop_order_pay_status=0;
 
         if(!isset($lastid)){
         	$lastid='0';
         }
         
         //接收从对账记录过来传入的参数
         $getdata=Yii::$app->request->getQueryParams();
         if(isset($getdata['id'])){
         $lastid=$getdata['id'];
         }
        
         $searchModel->finance_record_log_id=$lastid;
        //状态处理
        $dataProvider = $searchModel->search();
        $decss=Yii::$app->request->getQueryParams();
        if(isset($decss['FinancePopOrderSearch'])){
        $sta= $decss['FinancePopOrderSearch']['finance_pop_order_pay_status_type'];
         
        }else{
        $sta='';
        } 
        
        //通过账期id查找渠道id
        
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        	'ordedatainfo' => $tyu,
        	'statusdeflde'=>$sta,
        	'lastidRecordLogid'=>$lastid,   //账期id
        	'paydat'=>$paydat, 	
        	'channleid'=>$model->get_channleid($lastid),   //账期id
        	
        		
        ]);
    }

    /**
    * 我有第三方没有的需要去订单表查查询
    * @date: 2015-10-8
    * @author: peak pan
    * @return:
    **/
    
    public function actionOrderlist()
    { 
    	//获取下单渠道
    	$tyu=\core\models\operation\OperationOrderChannel::getorderchannellist('all');
    	$dateinfo=Yii::$app->request->getQueryParams();
    	if($dateinfo['id']){
    	$info=FinanceRecordLogSearch::get_financerecordloginfo($dateinfo['id']);
    	
    	if($info->finance_pay_channel_id=='10' && $info->finance_pay_channel_id=='7' &&$info->finance_pay_channel_id=='8' && $info->finance_pay_channel_id=='12' ){
    		//银联充值
    		echo  '开发中....';exit;
    	}else {
    		//渠道下单	 开始时间   我有三没有开始处理 从订单表里面开始查询
    		$searchModel= new OrderSearch;
    		$searchModel->load(Yii::$app->request->getQueryParams());
    		$searchModel->created_at=$info->finance_record_log_statime;
    		$searchModel->finance_record_log_endtime=$info->finance_record_log_endtime;
    		$searchModel->order_pop_order_code=FinancePopOrder::get_in_list_id($dateinfo['id']);
    		//$searchModel->channel_id=$info->finance_pay_channel_id;
    		$dataProvider = $searchModel->searchpoplist();
    	}
    	return $this->render('orderlist', [
    			'dataProvider' => $dataProvider,
    			'searchModel' => $searchModel,
    			'id'=>$dateinfo['id']?$dateinfo['id']:0,
    			]);
    	
    	}else {
    		return $this->redirect(['index']);
    	}
    }
    
    
    /**
    * 充值对账管理控制器
    * @date: 2015-10-9
    * @author: peak pan
    * @return:
    **/
    
    public function actionGeneralpaylist()
    { 
    	//获取下单渠道
    	$tyu= \core\models\operation\OperationOrderChannel::getorderchannellist('all');
    	//我有三没有开始处理 从订单表里面开始查询
    	
    	
    	$dateinfo=Yii::$app->request->getQueryParams();
    	if($dateinfo['id']){
    		$info=FinanceRecordLogSearch::get_financerecordloginfo($dateinfo['id']);
    		 
    		if($info->finance_pay_channel_id=='10' && $info->finance_pay_channel_id=='7' &&$info->finance_pay_channel_id=='8' && $info->finance_pay_channel_id=='12' ){
    			//银联充值
    			$searchModel= new PaymentSearch;
    			$searchModel->load(Yii::$app->request->getQueryParams());
    			$searchModel->finance_record_log_statime=$info->finance_record_log_statime;
    			$searchModel->finance_record_log_endtime=$info->finance_record_log_endtime;
    			$searchModel->general_pay_transaction_id=FinancePopOrder::get_in_list_id($dateinfo['id']);
    			$dataProvider = $searchModel->searchpaylist();
    			return $this->render('generalpaylist', [
    					'dataProvider' => $dataProvider,
    					'searchModel' => $searchModel,
    					'id' => $dateinfo['id']?$dateinfo['id']:0,
    					]);
    		}else {
    			\Yii::$app->getSession()->setFlash('default','无充值订单！');
    			return $this->redirect(['orderlist','id' =>$dateinfo['id']?$dateinfo['id']:0]);
    }
    }
    }
    /**
    * 坏账列表
    * @date: 2015-9-27
    * @author: peak pan
    * @return:
    **/
    public function actionBad()
    {
    	//获取下单渠道
    	$tyu= \core\models\operation\OperationOrderChannel::getorderchannellist('all');
    	
    	$searchModel = new FinancePopOrderSearch;
    	$searchModel->load(Yii::$app->request->getQueryParams());
    	$searchModel->is_del=0;
    	$dateinfo=Yii::$app->request->getQueryParams();
    	if(isset($dateinfo['FinancePopOrderSearch']['finance_pop_order_pay_status'])){
    		$searchModel->finance_pop_order_pay_status=$dateinfo['FinancePopOrderSearch']['finance_pop_order_pay_status'];
    	}else{
    		$searchModel->finance_pop_order_pay_status=[3,5];	
    	}
    	$dataProvider = $searchModel->search();
    	return $this->render('bad', [
    			'dataProvider' => $dataProvider,
    			'searchModel' => $searchModel,
    			'ordedatainfo' => $tyu,
    			]);
    }
    
    
    /**
    * 批量操作第三方订单
    * @date: 2015-10-9
    * @author: peak pan
    * @return:
    **/
    
    public function actionIndexall()
    {
    	$searchModel = new FinancePopOrderSearch;
    	$requestModel = Yii::$app->request->post();
		//$idArr = implode(',',);
    	if(!empty($requestModel) && array_key_exists('ids',$requestModel)){
		//checked($order_id)
		
    		
		foreach ($requestModel['ids'] as $iddate){
			$model=$searchModel::findOne($iddate);
			if(isset($model->order_code)){
			//如果有		
			if(strlen($model->order_code)==15){
			//判断是充值还是下单
			Order::checked($model->order_code);	
			}		
			}
			$model->finance_pop_order_finance_time=time();
			$model->finance_pop_order_pay_status='1';
			$model->save();
		}
		}else{
			\Yii::$app->getSession()->setFlash('default','请选择需要处理的数据！');
			return $this->redirect(['index']);
		}
		
		 return $this->redirect(['index']);
    }
    
    
    /**
    * 标记为坏账
    * @date: 2015-10-13
    * @author: peak pan
    * @return:
    **/
    public function actionTagssign()
    {
    	$searchModel = new FinancePopOrderSearch;
    	$requestModel = Yii::$app->request->get();
    		$model=$searchModel::findOne($requestModel['id']);
    		if($requestModel['edit']=='bak'){
    	    //坏账还原
    		$model->finance_pop_order_pay_status='0';
    		$url='index';
    		}elseif($requestModel['edit']=='bakinfo'){
    		//回滚财务审核	
    		$model->finance_pop_order_pay_status='0';
    		$url='index';
    		}elseif ($requestModel['edit']=='bakinfoyes'){
    		//财务审核已经处理的数据 
    		$model->finance_pop_order_pay_status='4';
    		$url='billinfo';
    		}elseif ($requestModel['edit']=='bakinfodabyes'){
    		$model->finance_pop_order_pay_status='5';
    		$url='bad';
    		}else{	
    		$model->finance_pop_order_pay_status='3';
    		$url='index';
    		}
    		$model->save();
    		return $this->redirect([$url, 'id' =>$requestModel['oid']]);
    }
      

   /**
   * 我们有第三方没有财务批量处理到订单表控制器
   * @date: 2015-10-9
   * @author: peak pan
   * @return:
   **/

    public function actionIndexmepost()
    {
    	/*林红优提供接口，目前为提供  */
    	
    	/* $searchModel = new FinancePopOrderSearch;
    	$requestModel = Yii::$app->request->post();
    	//$idArr = implode(',',);
    	foreach ($requestModel['ids'] as $iddate){
    		$model=$searchModel::findOne($iddate);
    		$model->finance_pop_order_pay_status='1';
    		$model->save();
    	} */
    	return $this->redirect(['index']);
    }
    
    /**
    * 我们有第三方没有财务批量处理到充值表控制器
    * @date: 2015-10-9
    * @author: peak pan
    * @return:
    **/
    public function actionGeneralmepost()
    {
    	/*胜强提供接口，目前未提供  */
    	 $searchModel = new FinancePopOrderSearch;
    	 $requestModel = Yii::$app->request->post();
    	//$GeneralPay= new GeneralPayController;
    	//foreach ($requestModel['ids'] as $iddate){
    	//$GeneralPay->modifyRecontiliation($iddate,1);
    	//} 
    	return $this->redirect(['index']);
    }
    
    
    /**
    * 查看和修改公用方法
    * @date: 2015-9-23
    * @author: peak pan
    * @return:
    **/
    public function actionView($id)
    {
        $model = $this->findModel($id);
		//添加
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	
        return $this->redirect(['view', 'id' => $model->id]);
        
        } else {
        //查看
        return $this->render('view', ['model' => $model]);
        
		}
    }

    /**
    * 公用添加方法
    * @date: 2015-9-23
    * @author: peak pan
    * @return:
    **/
    public function actionCreate()
    {
        $model = new FinancePopOrder;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    
    /**
    * 修改方法
    * @date: 2015-9-23
    * @author: peak pan
    * @return:
    **/
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
   * 返回坏账记录标示框
   * @date: 2015-10-20
   * @author: peak pan
   * @return:
   **/
    public function actionForminfo()
    {
    	$post=\Yii::$app->request->post();
    	if($post){
    	$searchModel = new FinancePopOrderSearch;
    	$requestModel = Yii::$app->request->get();
    	$model=$searchModel::findOne($requestModel['id']);
    	
    	
    	//var_dump($requestModel);exit;
    	if($requestModel['edit']=='baksite'){
    		//回滚财务审核
    		$model->finance_pop_order_pay_status='3';
    		$model->finance_pop_order_finance_time=time();
    		$model->finance_pop_order_msg=$post['FinancePopOrder']['finance_pop_order_msg'];
    	}elseif ($requestModel['edit']=='baksiteinfo'){
    		$model->finance_pop_order_pay_status='1';
    		$model->finance_pop_order_finance_time=time();
    		$model->finance_pop_order_msg=$post['FinancePopOrder']['finance_pop_order_msg'];
    	}
    	$model->save();
    	
    	//告诉订单方财务标记
    	if(isset($model->order_code)){
    		//如果有
    		if(strlen($model->order_code)==15){
    			Order::checked($model->order_code);
    		}
    	}
    	
    	
    	return $this->redirect(['index', 'id' =>$requestModel['oid']]);
    	}else{
    		
    	$model = new FinancePopOrder;
    	return $this->renderAjax('forminfo',['workerVacationModel'=>$model]);
    	
    	}
    }
    
      
   /**
   * 公用删除方法
   * @date: 2015-9-23
   * @author: peak pan
   * @return:
   **/
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
    * 私有单一查询的Model，使用本类
    * @date: 2015-9-23
    * @author: peak pan
    * @return:
    **/
    protected function findModel($id)
    {
        if (($model = FinancePopOrder::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('所请求的页面不存在.');
        }
    }
    
    /**
    * 对账统计方法
    * @date: 2015-9-23
    * @author: peak pan
    * @return:
    **/
    public function actionBillcount(){
    	$searchModel = new FinancePopOrderSearch;
    	$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
    	return $this->render('index', [
    			'dataProvider' => $dataProvider,
    			'searchModel' => $searchModel,
    			]);
    	
    }
    
    
    /**
    * 对账详情
    * @date: 2015-9-23
    * @author: peak pan
    * @return:
    **/
    public function actionBillinfo(){
    	
    	//获取下单渠道
    	$tyu= \core\models\operation\OperationOrderChannel::getorderchannellist('all');
    	$searchModel = new FinancePopOrderSearch;
    	$searchModel->load(Yii::$app->request->getQueryParams());
    	$searchModel->is_del=0;
    	$searchModel->finance_pop_order_pay_status=[1,4];
    	$timeinfo=Yii::$app->request->getQueryParams();
    	if(isset($timeinfo['FinancePopOrderSearch']['finance_order_channel_statuspayment'])){
    		$searchModel->finance_order_channel_statuspayment=strtotime($timeinfo['FinancePopOrderSearch']['finance_order_channel_statuspayment']);
    	}
    	if(isset($timeinfo['FinancePopOrderSearch']['finance_order_channel_endpayment'])){
    		$searchModel->finance_order_channel_statuspayment=strtotime($timeinfo['FinancePopOrderSearch']['finance_order_channel_endpayment']);
    	}
    	if(isset($timeinfo['FinancePopOrderSearch']['finance_pop_order_pay_status'])){
    		$searchModel->finance_pop_order_pay_status=$timeinfo['FinancePopOrderSearch']['finance_pop_order_pay_status'];
    	}
    	
    	$dataProvider = $searchModel->search();
    	return $this->render('billinfo', [
    			'dataProvider' => $dataProvider,
    			'searchModel' => $searchModel,
    			'ordedatainfo' => $tyu,
    			]);
    	 
    }

    
    
    
}
