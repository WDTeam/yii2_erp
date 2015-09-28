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
    		
    		$model->finance_uplod_url = UploadedFile::getInstance($model, 'finance_uplod_url');
    		if ($model->finance_uplod_url && $model->validate()) {
    			$path='upload/';
    			if(!file_exists($path))mkdir($path);
    			$filenamesitename=$model->finance_uplod_url->baseName;
    			$filename=time().'.'.$model->finance_uplod_url->extension;
    			if(!$model->finance_uplod_url->saveAs($path.$filename)){
    				return ["result"=>"Fail"];
    			}
    		}
    		 
    		$filePath = $path.$filename;
    		//	$filePath = './uploads/14430836465880.xls'; // 要读取的文件的路径
    		$objPHPExcel = \PHPExcel_IOFactory::load($filePath);
    		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
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
    		}
    		foreach ($sheetData as $key=>$value){
    			//去除表头
    			if($n>2){
    			$statusinfo=$model->PopOrderstatus($alinfo,$value);
    			$post['FinancePopOrder']['finance_pop_order_number'] =$statusinfo['order_channel_order_num'];
    			$post['FinancePopOrder']['finance_order_channel_id'] =$statusinfo['channel_id'];
    			$post['FinancePopOrder']['finance_order_channel_title'] =$statusinfo['order_channel_name'];
    			$post['FinancePopOrder']['finance_pay_channel_id'] =$statusinfo['pay_channel_id'];
    			$post['FinancePopOrder']['finance_pay_channel_title'] =$statusinfo['order_pay_channel_name'];
    			$post['FinancePopOrder']['finance_pop_order_customer_tel'] =$statusinfo['order_customer_phone'];
    			$post['FinancePopOrder']['finance_pop_order_worker_uid'] =$statusinfo['worker_id'];
    			$post['FinancePopOrder']['finance_pop_order_booked_time'] =$statusinfo['order_booked_begin_time'];
    			$post['FinancePopOrder']['finance_pop_order_booked_counttime'] =$statusinfo['order_booked_end_time'];// 按照分钟计算 
    			$post['FinancePopOrder']['finance_pop_order_sum_money'] =$statusinfo['order_money'];
    			//优惠卷金额
    			$post['FinancePopOrder']['finance_pop_order_coupon_count'] =$statusinfo['order_use_coupon_money']; 
    			//优惠卷id
    			$post['FinancePopOrder']['finance_pop_order_coupon_id'] =$statusinfo['coupon_id'];  
    			$post['FinancePopOrder']['finance_pop_order_order2'] =$statusinfo['order_code'];
    			
    			//获取渠道唯一订单号有问题需要问问
    			$post['FinancePopOrder']['finance_pop_order_channel_order'] =$statusinfo['order_channel_order_num'];
    			
    			$post['FinancePopOrder']['finance_pop_order_order_type'] =$statusinfo['order_service_type_id'];
    			$post['FinancePopOrder']['finance_pop_order_order_type'] =$statusinfo['order_service_type_id'];
    			$post['FinancePopOrder']['finance_pop_order_status'] =$statusinfo['order_before_status_dict_id'];
    			
    			$post['FinancePopOrder']['finance_pop_order_finance_isok'] =0;
    			$post['FinancePopOrder']['finance_pop_order_discount_pay'] =$statusinfo['order_use_coupon_money'];
    			
    			$post['FinancePopOrder']['finance_pop_order_reality_pay'] =$statusinfo['order_pay_money'];
    			$post['FinancePopOrder']['finance_pop_order_order_time'] =$statusinfo['created_at'];
    			$post['FinancePopOrder']['finance_pop_order_pay_time'] =$statusinfo['created_at'];
    			$post['FinancePopOrder']['finance_pop_order_pay_status'] =0;//财务确定处理按钮状态
    			$post['FinancePopOrder']['finance_pop_order_pay_status_type'] =$statusinfo['finance_pop_order_pay_status_type'];//财务确定处理按钮状态
    			$post['FinancePopOrder']['finance_pop_order_pay_title'] = $filenamesitename;
    			$post['FinancePopOrder']['finance_pop_order_check_id'] = Yii::$app->user->id;
    			$post['FinancePopOrder']['finance_pop_order_finance_time'] = 0;//财务对账提交时间
    			
    			$post['FinancePopOrder']['finance_order_channel_statuspayment'] =strtotime( $post['FinancePopOrderSearch']['finance_order_channel_statuspayment']);
    			$post['FinancePopOrder']['finance_order_channel_endpayment'] =strtotime($post['FinancePopOrderSearch']['finance_order_channel_endpayment']);
    			
    			$post['FinancePopOrder']['create_time'] = time();
    			$post['FinancePopOrder']['is_del'] =0;

    			$_model = clone $model;
    			$_model->setAttributes($post['FinancePopOrder']);
    			$_model->save();
    			unset($post['FinancePopOrder']);
    		}
    		
    		$n++;
    		}
    		
    	 	$FinanceRecordLog = new FinanceRecordLogSearch;
    		//获取渠道唯一订单号有问题需要问问
    		$post['FinanceRecordLog']['finance_order_channel_id'] =1;
    		//对账名称
    		$post['FinanceRecordLog']['finance_order_channel_name'] =$filenamesitename;
    		//收款渠道id
    		$post['FinanceRecordLog']['finance_pay_channel_id'] =$channelid;
    		
    		//$modelPay = new FinancePayChannelSearch;
    		$modelesr= new FinanceOrderChannelSearch;
    		$ordername=$modelesr->searchfind(array('id'=>$channelid),'finance_order_channel_name');
    		
    		
    		//收款渠道名称
    		$post['FinanceRecordLog']['finance_pay_channel_name'] =$ordername;
    		//成功记录数
    		$post['FinanceRecordLog']['finance_record_log_succeed_count'] =$modelinfo::find()->andWhere(['finance_pop_order_pay_status_type' => '1'])->count('id'); 
    		
    		$sumt=$modelinfo::find()->select(['sum(finance_pop_order_sum_money) as sumoney'])
    		->andWhere(['finance_pop_order_pay_status_type' => '1'])->asArray()->all();
    		$post['FinanceRecordLog']['finance_record_log_succeed_sum_money'] =$sumt[0]['sumoney'];
    		//人工确认笔数
    		$post['FinanceRecordLog']['finance_record_log_manual_count'] =0;
    	
    		//人工确认金额
    		$post['FinanceRecordLog']['finance_record_log_manual_sum_money'] =$statusinfo['created_at']?$statusinfo['created_at']:'0';
    		//失败笔数
    		$post['FinanceRecordLog']['finance_record_log_failure_count'] =$modelinfo::find()->andWhere(['finance_pop_order_pay_status_type' => '4'])->count('id'); 
    		
    		//失败总金额
    		$sumterr=$modelinfo::find()->select(['sum(finance_pop_order_sum_money) as sumoneyinfo'])
    		->andWhere(['finance_pop_order_pay_status_type' => '4'])->asArray()->all();
    		$post['FinanceRecordLog']['finance_record_log_failure_money'] =$sumterr[0]['sumoneyinfo'];

    		//对账人
    		$post['FinanceRecordLog']['finance_record_log_confirm_name'] =Yii::$app->user->identity->username;
    		//服务费
    		$post['FinanceRecordLog']['finance_record_log_fee'] = 0;
    		
    		$post['FinanceRecordLog']['create_time'] = time();
    		$post['FinanceRecordLog']['is_del']=0;
    		$_model = clone $FinanceRecordLog;
    		$_model->setAttributes($post['FinanceRecordLog']);
    		$_model->save(); 
    		return $this->redirect(['index']);
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
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        $decss=Yii::$app->request->getQueryParams();
        if(isset($decss['FinancePopOrderSearch'])){
        $sta= $decss['FinancePopOrderSearch']['finance_pop_order_pay_status_type'];
        }else{
        $sta='';
        }
       
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        	'ordedatainfo' => $tyu,
        	'statusdeflde'=>$sta	
 		
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
    	$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
    	return $this->render('billinfo', [
    			'dataProvider' => $dataProvider,
    			'searchModel' => $searchModel,
    			'ordedatainfo' => $tyu,
    			]);
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['view', 'id' => $model->id]);
        } else {
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
            throw new NotFoundHttpException('The requested page does not exist.');
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
    	$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
    	return $this->render('billinfo', [
    			'dataProvider' => $dataProvider,
    			'searchModel' => $searchModel,
    			'ordedatainfo' => $tyu,
    			]);
    	 
    }

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
