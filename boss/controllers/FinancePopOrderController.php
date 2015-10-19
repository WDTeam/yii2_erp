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

namespace boss\controllers;

use Yii;
use common\models\FinancePopOrder;
use boss\models\FinancePopOrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\models\FinanceOrderChannel;
use common\models\FinanceHeader;
use boss\models\FinanceRecordLogSearch;
use boss\models\FinanceOrderChannelSearch;
use core\models\order\OrderSearch;
use core\models\GeneralPay\GeneralPaySearch;
use boss\controllers\GeneralPayController;
use common\models\FinanceRecordLog;
use crazyfd\qiniu\Qiniu;
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
    		if(false){
    		//开启七牛上传，文件存储在七牛
    			$data = \Yii::$app->request->post();
    			$model->load($data);
    			$file = UploadedFile::getInstance($model, 'finance_uplod_url');
    			
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
    
    		//$filePath = './uploads/14430836465880.xls'; // 要读取的文件的路径
    		$objPHPExcel = \PHPExcel_IOFactory::load($filePath);
    		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
    		
    		//var_dump($sheetData);exit;
    		
    		header("Content-Type: text/html; charset=utf-8");
    		$post = Yii::$app->request->post();
    		
    		//通过渠道查询对应的表头信息
    		$channelid=$post['FinancePopOrderSearch']['finance_order_channel_id'];
    		
    		$order_channel = new FinanceHeader;
    		$alinfo=FinanceHeader::find()
    		->select('finance_header_where,finance_header_key')
    		->andWhere(['!=','finance_header_where','0'])
    		->andWhere(['=','finance_order_channel_id',$channelid])
    		->asArray()->All();
    		$n=1;
    		//验证上传的表头和选择的是否一致
    		$statusinfo=$model->id_header($sheetData[1],$channelid);
    		
    		if($statusinfo){
    			\Yii::$app->getSession()->setFlash('default','对不起你上传的表不对！'); 
    		  return $this->redirect(['index']);
    		}else {
    		//向记录表里面插入一条空数据	
    		$FinanceRecordLog = new FinanceRecordLogSearch;
    		$FinanceRecordLog->insert();
    		$lastidRecordLog=$FinanceRecordLog->id;
    		}
    		if($lastidRecordLog==0){ \Yii::$app->getSession()->setFlash('default','账期出现问题，请重新上传！'); 
    		  return $this->redirect(['index']);
    		 }
    		
    		foreach ($sheetData as $key=>$value){
    			//去除表头
    			if($n>1 && !empty($value['A'])){
    			$statusinfo=$model->PopOrderstatus($alinfo,$value,$channelid);
    			//var_dump($statusinfo);exit;
    			$postdate['finance_record_log_id'] =$lastidRecordLog;
    			$postdate['finance_pop_order_number'] =$statusinfo['order_channel_order_num'];
    			$postdate['finance_order_channel_id'] =$channelid;
    			$postdate['finance_order_channel_title'] =FinanceOrderChannel::getOrderChannelByName($channelid);
    			$postdate['finance_pay_channel_id'] =$statusinfo['pay_channel_id'];
    			$postdate['finance_pay_channel_title'] =$statusinfo['order_pay_channel_name'];
    			$postdate['finance_pop_order_customer_tel'] =$statusinfo['order_customer_phone'];
    			$postdate['finance_pop_order_worker_uid'] =$statusinfo['worker_id'];
    			$postdate['finance_pop_order_booked_time'] =$statusinfo['order_booked_begin_time'];
    			$postdate['finance_pop_order_booked_counttime'] =$statusinfo['order_booked_end_time'];// 按照分钟计算 
    			$postdate['finance_pop_order_sum_money'] =$statusinfo['order_money']; //总金额
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
    			
    			$postdate['finance_pop_order_finance_time'] = 0;//财务对账提交时间
    			
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
    	 	$FinanceRecordLog = new FinanceRecordLogSearch;
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
    		$customer_info->finance_record_log_manual_sum_money =$statusinfo['created_at']?$statusinfo['created_at']:'0';
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
    		$customer_info->finance_record_log_fee =FinancePopOrderSearch::get_fee_pay($channelid,$lastidRecordLog);
    		$customer_info->finance_record_log_statime =strtotime($post['FinancePopOrderSearch']['finance_order_channel_statuspayment']);
    		$customer_info->finance_record_log_endtime =strtotime($post['FinancePopOrderSearch']['finance_order_channel_endpayment']);
    		$customer_info->finance_record_log_qiniuurl =$qiniuurl;
    		$customer_info->create_time= time();
    		$customer_info->is_del=0;
    		$customer_info->save();
    		return $this->redirect(['index','id' =>$lastidRecordLog]);
    	}

  		##########################
		//输出部分
       $ordedata= new FinanceOrderChannel;
        $ordewhere['is_del']=0;
        $ordewhere['finance_order_channel_is_lock']=1;
        $payatainfo=$ordedata::find()->where($ordewhere)->asArray()->all();
        foreach ($payatainfo as $errt){
        	$tyd[]=$errt['id'];
        	$tydtui[]=$errt['finance_order_channel_name'];
        }
         $tyu= array_combine($tyd,$tydtui);
         
         $searchModel = new FinancePopOrderSearch;
         //默认条件
         $searchModel->load(Yii::$app->request->getQueryParams());
         $searchModel->is_del=0;
         $searchModel->finance_pop_order_pay_status=0;
 
         /* if(isset($lastidRecordLog)){
         	\Yii::$app->cache->set('lastidinfoid',$lastidRecordLog,600);
         }
         $lastid=FinancePopOrder::get_cache_tiem(); */
         
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
    	//输出部分
    	$ordedata= new FinanceOrderChannel;
    	$ordewhere['is_del']=0;
    	$ordewhere['finance_order_channel_is_lock']=1;
    	$payatainfo=$ordedata::find()->where($ordewhere)->asArray()->all();
    	foreach ($payatainfo as $errt){
    		$tyd[]=$errt['id'];
    		$tydtui[]=$errt['finance_order_channel_name'];
    	}
    	$tyu= array_combine($tyd,$tydtui);
    	$sta=3;
    		
    			//我有三没有开始处理 从订单表里面开始查询
    			$searchModel= new OrderSearch;
    			$financerecordloginfo=FinanceRecordLogSearch::get_financerecordloginfo($sta);
    			if($financerecordloginfo){
    				//$search_infoModel->created_at=$financerecordloginfo['finance_record_log_statime'];
    				//$searchModel_info->created_at=$financerecordloginfo['finance_record_log_endtime'];
    				$searchModel->channel_id=$sta;
    			}
    			$searchModel->channel_id=$sta;
    			$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());	
    	return $this->render('orderlist', [
    			'dataProvider' => $dataProvider,
    			'searchModel' => $searchModel,
    			]);

    }
    
    
    /**
    * 充值对账管理控制器
    * @date: 2015-10-9
    * @author: peak pan
    * @return:
    **/
    
    public function actionGeneralpaylist()
    { 
    	//输出部分
    	$ordedata= new FinanceOrderChannel;
    	$ordewhere['is_del']=0;
    	$ordewhere['finance_order_channel_is_lock']=1;
    	$payatainfo=$ordedata::find()->where($ordewhere)->asArray()->all();
    	foreach ($payatainfo as $errt){
    		$tyd[]=$errt['id'];
    		$tydtui[]=$errt['finance_order_channel_name'];
    	}
    	$tyu= array_combine($tyd,$tydtui);
    	//我有三没有开始处理 从订单表里面开始查询
    	$searchModel= new GeneralPaySearch;
    	$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
    	return $this->render('generalpaylist', [
    			'dataProvider' => $dataProvider,
    			'searchModel' => $searchModel,
    			]);
    
    }

    
    /**
    * 坏账列表
    * @date: 2015-9-27
    * @author: peak pan
    * @return:
    **/
    public function actionBad()
    {
    	$ordedata= new FinanceOrderChannel;
    	$ordewhere['is_del']=0;
    	$ordewhere['finance_order_channel_is_lock']=1;
    	$payatainfo=$ordedata::find()->where($ordewhere)->asArray()->all();
    	
    	foreach ($payatainfo as $errt){
    		$tyd[]=$errt['id'];
    		$tydtui[]=$errt['finance_order_channel_name'];
    	}
    	
    	$tyu= array_combine($tyd,$tydtui);
    	
    	$searchModel = new FinancePopOrderSearch;
    	$searchModel->load(Yii::$app->request->getQueryParams());
    	$searchModel->is_del=0;
    	$searchModel->finance_pop_order_pay_status=3;
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
		foreach ($requestModel['ids'] as $iddate){
			$model=$searchModel::findOne($iddate);
			$model->finance_pop_order_pay_status='1';
			$model->save();
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
    		
    		}elseif($requestModel['edit']=='bakinfo'){
    		//回滚财务审核	
    		$model->finance_pop_order_pay_status='0';
    		}else{	
    		$model->finance_pop_order_pay_status='3';
    		}
    		$model->save();
    		return $this->redirect(['index', 'id' =>$requestModel['oid']]);
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
    	$GeneralPay= new GeneralPayController;
    	foreach ($requestModel['ids'] as $iddate){
    	$GeneralPay->modifyRecontiliation($iddate,1);
    	} 
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
    	
    	$ordedata= new FinanceOrderChannel;
    	$ordewhere['is_del']=0;
    	$ordewhere['finance_order_channel_is_lock']=1;
    	$payatainfo=$ordedata::find()->where($ordewhere)->asArray()->all();
    	foreach ($payatainfo as $errt){
    		$tyd[]=$errt['id'];
    		$tydtui[]=$errt['finance_order_channel_name'];
    	}
    	$tyu= array_combine($tyd,$tydtui);
    	
    	$searchModel = new FinancePopOrderSearch;
    	$searchModel->load(Yii::$app->request->getQueryParams());
    	$searchModel->is_del=0;
    	$timeinfo=Yii::$app->request->getQueryParams();
    	if(isset($timeinfo['FinancePopOrderSearch']['finance_order_channel_statuspayment'])){
    		$searchModel->finance_order_channel_statuspayment=strtotime($timeinfo['FinancePopOrderSearch']['finance_order_channel_statuspayment']);
    	}
    	if(isset($timeinfo['FinancePopOrderSearch']['finance_order_channel_endpayment'])){
    		$searchModel->finance_order_channel_statuspayment=strtotime($timeinfo['FinancePopOrderSearch']['finance_order_channel_endpayment']);
    	}
    	
    	$dataProvider = $searchModel->search();
    	return $this->render('billinfo', [
    			'dataProvider' => $dataProvider,
    			'searchModel' => $searchModel,
    			'ordedatainfo' => $tyu,
    			]);
    	 
    }

    
    
    
}
