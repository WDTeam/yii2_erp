<?php

namespace boss\controllers\finance;

use Yii;
use common\models\finance\FinanceRefund;
use boss\models\finance\FinanceRefundSearch;
use boss\components\BaseAuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\finance\FinanceOrderChannel;
use PHPExcel;
use PHPExcel_IOFactory;

/**
 * FinanceRefundController implements the CRUD actions for FinanceRefund model.
 */
class FinanceRefundController extends BaseAuthController
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
     * Lists all FinanceRefund models.
     * @return mixed
     */
    public function actionIndex()
    {
         //获取下单渠道
         $tyu= FinanceOrderChannel::get_order_channel_listes(); 
         
        $searchModel = new FinanceRefundSearch;
        $searchModel->load(Yii::$app->request->getQueryParams());
        $searchModel->statusstype='index';
        $searchModel->isstatus='4';
        $dataProvider = $searchModel->search();
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        	'ordedat' => $tyu,
        ]);
    }

    
    
   /**
   * 退款统计
   * @date: 2015-10-21
   * @author: peak pan
   * @return:
   **/
    public function actionCountinfo()
    {
    	$searchModel = new FinanceRefundSearch;
    	//var_dump(Yii::$app->request->getQueryParams());exit;
    	$searchModel->load(Yii::$app->request->getQueryParams());
    	$searchModel->isstatus='4';
    	$dataProvider = $searchModel->search();
    	return $this->render('countinfo', [
    			'dataProvider' => $dataProvider,
    			'searchModel' => $searchModel,
    			]);
    }
    
    
    
    
    
    /**
     * Displays a single FinanceRefund model.
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
     * Creates a new FinanceRefund model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FinanceRefund;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    
    /**
    * 开始退款
    * @date: 2015-10-21
    * @author: peak pan
    * @return:
    **/
    
    public function actionRefund()
    {
    	    $requestModel = Yii::$app->request->get();
    		$model=FinanceRefund::findOne($requestModel['id']);
    		if($requestModel['edit']=='baksite'){
    	    //退款
    			$obj = new \core\models\payment\GeneralPayRefund();
    			if(!isset($model->finance_refund_pop_nub) || !isset($model->customer_id)){
    			\Yii::$app->getSession()->setFlash('default','充值记录查询无此记录,退款失败！');
    			return $this->redirect(['index', 'id' =>$requestModel['id']]);
    			}
    			$s = $obj->call_pay_refund($model->finance_refund_pop_nub,$model->customer_id);
    			if($s){
    				//成功
    				$model->finance_pop_order_pay_status='4';
    			}else{
    				
    				//失败就回滚
    				$model->finance_pop_order_pay_status='2';
    			}
    		}else{	
    		$model->finance_pop_order_pay_status='2';
    		}
    		$model->save();
    		
    		if(!$s || $requestModel['edit']=='baksite'){
    			\Yii::$app->getSession()->setFlash('default','充值记录查询无此记录,退款失败！');
    			return $this->redirect(['index', 'id' =>$requestModel['id']]);
    		}else{
    			return $this->redirect(['index', 'id' =>$requestModel['id']]);
    		}	
    }
    
    
    
    
    /**
     * Updates an existing FinanceRefund model.
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
     * Deletes an existing FinanceRefund model.
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
     * Finds the FinanceRefund model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinanceRefund the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FinanceRefund::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
      
    public function actionExport(){
    	$data = FinanceRefund::find()->all();
    	$objPHPExcel=new PHPExcel();
    	ob_start();
    	$objPHPExcel->getProperties()->setCreator('ejiajie')
    	->setLastModifiedBy('ejiajie')
    	->setTitle('Office 2007 XLSX Document')
    	->setSubject('Office 2007 XLSX Document')
    	->setDescription('Document for Office 2007 XLSX, generated using PHP classes.')
    	->setKeywords('office 2007 openxml php')
    	->setCategory('Result file');
    	$objPHPExcel->setActiveSheetIndex(0)
    	->setCellValue('A1','用户电话')
    	->setCellValue('B1','退款申请时间')
    	->setCellValue('C1','退款金额')
    	->setCellValue('D1','退款理由')
    	->setCellValue('E1','优惠价格')
    	->setCellValue('F1','订单支付时间')
    	->setCellValue('G1','支付方式名称')
    	->setCellValue('H1','流水号')
    	->setCellValue('I1','支付状态')
    	->setCellValue('J1','服务阿姨')
    	->setCellValue('K1','阿姨电话')
    	->setCellValue('L1','地区')
    	->setCellValue('M1','财务处理人');
    	$i=2;
    	foreach($data as $k=>$v){
    		$objPHPExcel->setActiveSheetIndex(0)
    		->setCellValue('A'.$i,$v['finance_refund_tel'])
    		->setCellValue('B'.$i,date('Y-m-d H:i:s',$v['create_time']))
    		->setCellValue('C'.$i,$v['finance_refund_money'])
    		->setCellValue('D'.$i,$v['finance_refund_reason'])
    		->setCellValue('E'.$i,$v['finance_refund_discount'])
    		->setCellValue('F'.$i,date('Y-m-d H:i:s',$v['finance_refund_pay_create_time']))
    		->setCellValue('G'.$i,$v['finance_pay_channel_title'])
    		->setCellValue('H'.$i,$v['finance_refund_pay_flow_num'])
    		->setCellValue('I'.$i,$v['finance_refund_pay_status'])
    		->setCellValue('J'.$i,$v['finance_refund_worker_id'])
    		->setCellValue('K'.$i,$v['finance_refund_worker_tel'])
    		->setCellValue('L'.$i,$v['finance_refund_city_id'])
    		->setCellValue('M'.$i,$v['finance_refund_check_name']);
    		$i++;
    	}
    	$objPHPExcel->getActiveSheet()->setTitle('退款');
    	$objPHPExcel->setActiveSheetIndex(0);
    	$filename=urlencode('退款数据导出').'_'.date('Y-m-dHis');
    	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    	ob_end_clean();
    	header('Content-Type: application/vnd.ms-excel');
    	header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
    	header('Cache-Control: max-age=0');
    	$objWriter->save('php://output');
    	exit;
    }
    
    
    
    
    
    
}
