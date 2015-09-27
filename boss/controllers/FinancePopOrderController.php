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
use boss\controllers\FinanceRecordLogController;






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
    		foreach ($sheetData as $key=>$value){
    			//去除表头
    			if($n>2){	
    			$statusinfo=$model->PopOrderstatus($alinfo,$value);
    			$nameorder=$post['FinancePopOrder']['finance_pop_order_number']=$statusinfo['order_channel_order_num']?$statusinfo['order_channel_order_num']:'0';
    			$post['FinancePopOrder']['finance_pop_order_number'] =$statusinfo['order_channel_order_num']?$statusinfo['order_channel_order_num']:'0';
    			$post['FinancePopOrder']['finance_order_channel_id'] =$statusinfo['channel_id']?$statusinfo['channel_id']:'0';
    			$post['FinancePopOrder']['finance_order_channel_title'] =$statusinfo['order_channel_name']?$statusinfo['order_channel_name']:'0';
    			$post['FinancePopOrder']['finance_pay_channel_id'] =$statusinfo['pay_channel_id']?$statusinfo['pay_channel_id']:'0';
    			$post['FinancePopOrder']['finance_pay_channel_title'] =$statusinfo['order_pay_channel_name']?$statusinfo['order_pay_channel_name']:'0';
    			$post['FinancePopOrder']['finance_pop_order_customer_tel'] =$statusinfo['order_customer_phone']?$statusinfo['order_customer_phone']:'0';
    			$post['FinancePopOrder']['finance_pop_order_worker_uid'] =$statusinfo['worker_id']?$statusinfo['worker_id']:'0';
    			$post['FinancePopOrder']['finance_pop_order_booked_time'] =$statusinfo['order_booked_begin_time']?$statusinfo['order_booked_begin_time']:'0';
    			$post['FinancePopOrder']['finance_pop_order_booked_counttime'] =$statusinfo['order_booked_end_time']?$statusinfo['order_booked_end_time']:'0';// 按照分钟计算 
    			$post['FinancePopOrder']['finance_pop_order_sum_money'] =$statusinfo['order_money']?$statusinfo['order_money']:'0';
    			//优惠卷金额
    			$post['FinancePopOrder']['finance_pop_order_coupon_count'] =$statusinfo['order_use_coupon_money']?$statusinfo['order_use_coupon_money']:'0'; 
    			//优惠卷id
    			$post['FinancePopOrder']['finance_pop_order_coupon_id'] =$statusinfo['coupon_id']?$statusinfo['coupon_id']:'0';  
    			$post['FinancePopOrder']['finance_pop_order_order2'] =$statusinfo['order_code']?$statusinfo['order_code']:'0';
    			//获取渠道唯一订单号有问题需要问问
    			$post['FinancePopOrder']['finance_pop_order_channel_order'] =$statusinfo['order_channel_order_num']?$statusinfo['order_channel_order_num']:'0';
    			$post['FinancePopOrder']['finance_pop_order_order_type'] =$statusinfo['order_service_type_id']?$statusinfo['order_service_type_id']:'0';
    			$post['FinancePopOrder']['finance_pop_order_order_type'] =$statusinfo['order_service_type_id']?$statusinfo['order_service_type_id']:'0';
    			$post['FinancePopOrder']['finance_pop_order_status'] =$statusinfo['order_before_status_dict_id']?$statusinfo['order_before_status_dict_id']:'0';
    			$post['FinancePopOrder']['finance_pop_order_finance_isok'] =0;
    			$post['FinancePopOrder']['finance_pop_order_discount_pay'] =$statusinfo['order_use_coupon_money']?$statusinfo['order_use_coupon_money']:'0';
    			$post['FinancePopOrder']['finance_pop_order_reality_pay'] =$statusinfo['order_pay_money']?$statusinfo['order_pay_money']:'0';
    			$post['FinancePopOrder']['finance_pop_order_order_time'] =$statusinfo['created_at']?$statusinfo['created_at']:'0';
    			$post['FinancePopOrder']['finance_pop_order_pay_time'] =$statusinfo['created_at']?$statusinfo['created_at']:'0';
    			$post['FinancePopOrder']['finance_pop_order_pay_status'] =0;//财务确定处理按钮状态
    			$post['FinancePopOrder']['finance_pop_order_pay_status_type'] =$statusinfo['finance_pop_order_pay_status_type']?$statusinfo['finance_pop_order_pay_status_type']:'0';//财务确定处理按钮状态
    			$post['FinancePopOrder']['finance_pop_order_pay_title'] = '对账成功';
    			$post['FinancePopOrder']['finance_pop_order_check_id'] = Yii::$app->user->id;
    			$post['FinancePopOrder']['finance_pop_order_finance_time'] = 0;//财务对账提交时间
    			$post['FinancePopOrder']['create_time'] = time();
    			$post['FinancePopOrder']['is_del'] =0;
    			$_model = clone $modelinfo;
    			$_model->setAttributes($post['FinancePopOrder']);
    			$_model->save();
    			unset($post['FinancePopOrder']);
    			//var_dump($post['FinancePopOrder']); exit;
    		}
    		$n++;
    		}
    		
    		
    		//记录本次导入的数据
    		/*
    		 * finance_order_channel_id	smallint(4)	YES	NULL	对账名称id
			finance_order_channel_name	varchar(100)	YES	NULL	对账名称
			finance_pay_channel_id	smallint(4)	YES	NULL	收款渠道id
			finance_pay_channel_name	varchar(100)	YES	NULL	收款渠道名称
			finance_record_log_succeed_count	smallint(6)	YES	NULL	成功记录数
			finance_record_log_succeed_sum_money	decimal(8,2)	YES	0.00	成功记录数总金额
			finance_record_log_manual_count	smallint(6)	YES	NULL	人工确认笔数
			finance_record_log_manual_sum_money	decimal(8,2)	YES	0.00	人工确认金额
			finance_record_log_failure_count	smallint(6)	YES	0	失败笔数
			finance_record_log_failure_money	decimal(8,2)	YES	0.00	失败总金额
			finance_record_log_confirm_name	varchar(30)	YES	NULL	对账人
			finance_record_log_fee	decimal(8,2)	YES	0.00	服务费
			create_time	int(10)	YES	NULL	创建时间
			is_del	smallint(1)	YES	0	0 正常 1 删除
    		 *  
    		 *  */
    		
    		$FinanceRecordLog = new FinanceRecordLogController;
    		
    		
    		
    		
    		
    		
    		
    		
    		
    		
    		
    		return $this->redirect(['index']);
    	}
 
    	
  		###################################################
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
        return $this->render('index', [
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
