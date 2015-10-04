<?php

namespace boss\controllers;
error_reporting(E_ALL);
use Yii;
use common\models\FinanceSettleApply;
use boss\models\FinanceSettleApplySearch;
use boss\models\FinanceSettleApplyLogSearch;
use boss\components\BaseAuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\FinanceWorkerOrderIncome;
use common\models\FinanceWorkerNonOrderIncome;
use boss\models\WorkerSearch;
use boss\models\FinanceWorkerOrderIncomeSearch;
use boss\models\FinanceWorkerNonOrderIncomeSearch;

/**
 * FinanceSettleApplyController implements the CRUD actions for FinanceSettleApply model.
 */
class FinanceSettleApplyController extends BaseAuthController
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
     * Lists all FinanceSettleApply models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FinanceSettleApplySearch;
        $defaultParams = array('FinanceSettleApplySearch'=>['finance_settle_apply_status' => '0']);
        $requestParams = Yii::$app->request->getQueryParams();
        $nodeId = null;
        if(isset($requestParams['FinanceSettleApplySearch'])){
            $requestModel = $requestParams['FinanceSettleApplySearch'];
            $nodeId =$requestModel['nodeId'];
        }
        $requestParams = array_merge($defaultParams,$requestParams);
        $dataProvider = $searchModel->search($requestParams);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'nodeId' => $nodeId,
        ]);
    }
    
    /**
     * Lists all FinanceSettleApply models.
     * @return mixed
     */
    public function actionQuery()
    {
        $workerSearchModel = new WorkerSearch;
        $financeSearchModel = new FinanceSettleApplySearch;
        $dataProvider = $financeSearchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('query', [
            'dataProvider' => $dataProvider,
            'searchModel' => $workerSearchModel,
        ]);
    }
    
    /**
     * 导出提交oa系统需要的excel表
     * 1.动态组装excel标题，例如"通州门店8月全时段家政人员订单费用表"
     * 2.动态组装表头信息，例如：‘店面’、‘姓名’、‘扣款’，‘阿姨所得’等
     * 3.写入每个阿姨实时的收入数据
     * @return type
     */
    public function actionExport(){
        
        $shopName = "通州门店";
        $settleMonth = "8月";
        $workerType = "全时段";
        $statDes = $shopName.$settleMonth.$workerType.'家政人员订单费用表';
        
        $financeSearchModel = new FinanceSettleApplySearch;
        $workerIncomeAndDetailArr = $financeSearchModel->getWorkerIncomeAndDetail();
        $workerIncomeAndDetail = $workerIncomeAndDetailArr[0];
        $financeWorkerNonOrderIncomeSearch = new FinanceWorkerNonOrderIncomeSearch;
        $nonOrderIncomeArr = $financeWorkerNonOrderIncomeSearch->getNonOrderIncomeBySettleApplyId($workerIncomeAndDetail['settleApplyId']);
        $nonOrderIncomeTypeAndMoneyArr = [];
        foreach($nonOrderIncomeArr as $nonOrderIncome){
            $nonOrderIncomeTypeAndMoneyArr["'".$nonOrderIncome['finance_worker_non_order_income_type']."'"] = $nonOrderIncome['finance_worker_non_order_income'];
        }
        $workerIncomeAndDetailReal = array_merge($nonOrderIncomeTypeAndMoneyArr,$workerIncomeAndDetail);
        
        $baseData = array('shop_name'=>'待定','worker_name'=>'待定','worker_idcard'=>'待定','worker_bank_card'=>'待定');
        //获取当前阿姨所有的补贴id和描述，按id排序
        $allWorkerSubsidyIdAndDes = [['rule_id'=>'1','rule_des'=>'路补'],['rule_id'=>'2','rule_des'=>'晚补'],['rule_id'=>'3','rule_des'=>'全勤奖']];
        $sheetColumnLeter = ['E','F','G','H','I','J'];
        $dynamicSheetHeaders = [];
        $dynamicSheetValues = [];
        $i = 0;
        foreach ($allWorkerSubsidyIdAndDes as $workerSubsidyIdAndDes){
            $dynamicSheetHeaders[$sheetColumnLeter[$i]]=$workerSubsidyIdAndDes['rule_des'];
            $dynamicSheetValues[$sheetColumnLeter[$i]] = "'".$workerSubsidyIdAndDes['rule_id']."'";
            $baseData["'".$workerSubsidyIdAndDes['rule_id']."'"]=0.00;
            $i++;
        }
        //查询结算申请表与worker表关联查询，之后单独查询非订单收入表，放入一个数组中，与baseData数组做merge操作
        
        $workerIncomeAndDetailToExcel = array_merge($baseData,$workerIncomeAndDetailReal);
        $data=array(
            0=>$workerIncomeAndDetailToExcel
           );
           $objPHPExcel=new \PHPExcel();
           $objPHPExcel->getProperties()->setCreator('http://www.jb51.net')
                   ->setLastModifiedBy('http://www.jb51.net')
                   ->setTitle('Office 2007 XLSX Document')
                   ->setSubject('Office 2007 XLSX Document')
                   ->setDescription('Document for Office 2007 XLSX, generated using PHP classes.')
                   ->setKeywords('office 2007 openxml php')
                   ->setCategory('Result file');
           $objPHPExcel->setActiveSheetIndex(0)
                       ->setCellValue('A1','店面')
                       ->setCellValue('B1','姓名')
                       ->setCellValue('C1','身份证号')
                       ->setCellValue('D1','银行卡号');
            foreach ($dynamicSheetHeaders as $dynamicSheetHeaderKey => $dynamicSheetHeaderValue){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($dynamicSheetHeaderKey.'1',$dynamicSheetHeaderValue);
            }
           $i=2;   
           foreach($data as $k=>$v){
            $objPHPExcel->setActiveSheetIndex(0)
                       ->setCellValue('A'.$i,$v['shop_name'])
                       ->setCellValue('B'.$i,$v['worker_name'])
                       ->setCellValue('C'.$i,$v['worker_idcard'])
                       ->setCellValue('D'.$i,$v['worker_bank_card']);
            foreach ($dynamicSheetHeaders as $dynamicSheetHeaderKey => $dynamicSheetHeaderValue){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($dynamicSheetHeaderKey.$i,$v[$dynamicSheetValues[$dynamicSheetHeaderKey]]);
            }
            
            $i++;
           }
           $objPHPExcel->getActiveSheet()->setTitle('结算');
           $objPHPExcel->setActiveSheetIndex(0);
           $filename=urlencode('阿姨结算统计表').'_'.date('Y-m-dHis');
           header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        return null;
    }
    
    /**
     * Lists all FinanceSettleApply models.
     * @return mixed
     */
    public function actionReview()
    {
        
        $searchModel = new FinanceSettleApplySearch;
        $requestModel = Yii::$app->request->getQueryParams();
        $financeSettleApplySearch = $requestModel["FinanceSettleApplySearch"];
        //结算id字符串，例如："234,345"
        $ids = $financeSettleApplySearch["ids"];
        $financeSettleApplyStatus = $financeSettleApplySearch["finance_settle_apply_status"];
        $idArr = explode(',', $ids);
        
        foreach($idArr as $id){
            $model = $this->findModel($id);
            $model->finance_settle_apply_status = $financeSettleApplyStatus;
            $model->save();
            $financeSettleApplyLogSearch = new FinanceSettleApplyLogSearch;
            $financeSettleApplyLogSearch->finance_settle_apply_id = $id;
            $financeSettleApplyLogSearch->finance_settle_apply_reviewer_id = Yii::$app->user->id;
            $financeSettleApplyLogSearch->finance_settle_apply_reviewer = Yii::$app->user->identity->username;
            $financeSettleApplyLogSearch->finance_settle_apply_node_id = abs($financeSettleApplyStatus);
            $financeSettleApplyLogSearch->finance_settle_apply_node_des = $searchModel->financeSettleApplyStatusArr[$financeSettleApplyStatus];
            $financeSettleApplyLogSearch->finance_settle_apply_is_passed = $financeSettleApplyStatus >0 ? 1:0;
            $financeSettleApplyLogSearch->finance_settle_apply_reviewer_comment ="";
            $financeSettleApplyLogSearch->created_at = time();
            $financeSettleApplyLogSearch->save();
        }
        return $this->actionIndex();
    }

    /**
     * Displays a single FinanceSettleApply model.
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
     * Creates a new FinanceSettleApply model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FinanceSettleApply;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FinanceSettleApply model.
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
     * Deletes an existing FinanceSettleApply model.
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
     * Finds the FinanceSettleApply model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinanceSettleApply the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FinanceSettleApply::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
    * 阿姨人工结算
    */
    public function actionWorkerManualSettlementIndex(){
        $financeSettleApplySearch= new FinanceSettleApplySearch;
        $requestModel = Yii::$app->request->getQueryParams();
        if(isset($requestModel["FinanceSettleApplySearch"])){
            $financeSettleApplySearch = $requestModel["FinanceSettleApplySearch"];
        }
        $financeSettleApplySearch = $financeSettleApplySearch->getWorkerInfo($workerId);//获取阿姨的信息
        $searchModel = new FinanceWorkerOrderIncomeSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('workerManualSettlementIndex', ['model'=>$financeSettleApplySearch,'dataProvider'=>$dataProvider]);
    }
    
    public function actionWorkerManualSettlementDone(){
        $requestModel = Yii::$app->request->getQueryParams();
        $financeSettleApplySearch = $requestModel["FinanceSettleApplySearch"];
//        saveAndGenerateSettleData($partimeWorkerArr,$settleStartTime,$settleEndTime);
        return $this->redirect(['index']);
    }
    
    /**
    * 本文件是用于兼职阿姨每周（例如：2015.9.21-2015.9.27）的结算
    * 1.获取当前在职的且是兼职的阿姨Id列表
    * 2.获取每个阿姨的身份证号，在2015.9.21-2015.9.27已完成的订单信息，
    *   包括订单Id、支付类型、订单金额、服务时长、服务完成的时间，
    *   保存到阿姨订单收入表
    * 3.获取每个阿姨Id，在2015.9.21-2015.9.27已完成订单的总收入信息，
    *   按收入类型分组的订单总金额，保存到结算表
    */
    public function actionPartTimeWorkerCycleSettlement(){
        $settleStartTime = date('Y-m-d 00:00:00', strtotime('-1 week last monday'));;//统计开始时间,上周第一天
        echo $settleStartTime.'------';
        $settleEndTime = date('Y-m-d 23:59:59', strtotime('last sunday'));//统计结束时间,上周最后一天
        echo $settleEndTime.'------';
        //获取阿姨的数组信息
        $partimeWorkerArr = array(['worker_id'=>'555','worker_name'=>'阿姨1','worker_idcard'=>'4210241983','worker_bank_card'=>'62217978'],['worker_id'=>'666','worker_name'=>'阿姨2','worker_idcard'=>'4210241984','worker_bank_card'=>'622174747']);
        $this->saveAndGenerateSettleData($partimeWorkerArr,$settleStartTime,$settleEndTime);
    }
    
    /**
    * 本文件是用于全职阿姨每月（例如：2015.9.1-2015.9.30）的结算
    * 1.获取当前在职的且是全职的阿姨Id列表
    * 2.获取每个阿姨，在2015.9.1-2015.9.30已完成的订单信息，
    *   包括订单Id、支付类型、订单金额、服务时长、服务完成的时间，
    *   保存到阿姨订单收入表
    * 3.获取每个阿姨Id，在2015.9.1-2015.9.30已完成订单的总收入信息，
    *   按收入类型分组的订单总金额，保存到结算表
    */
    public function actionFullTimeWorkerCycleSettlement(){
        $settleStartTime = strtotime(date('Y-m-01 00:00:00', strtotime('-1 month')));//统计开始时间,上个月的第一天
        echo date('Y-m-01 00:00:00', strtotime('-1 month')).'------';
        $settleEndTime = strtotime(date('Y-m-t 23:59:59', strtotime('-1 month')));//统计结束时间,上个月的最后一天
        echo date('Y-m-t 23:59:59', strtotime('-1 month')).'------';
        //获取阿姨的数组信息
        $partimeWorkerArr = array(['worker_id'=>'555','worker_name'=>'阿姨1','worker_idcard'=>'4210241983','worker_bank_card'=>'62217978'],['worker_id'=>'666','worker_name'=>'阿姨2','worker_idcard'=>'4210241984','worker_bank_card'=>'622174747']);
        $this->saveAndGenerateSettleData($partimeWorkerArr,$settleStartTime,$settleEndTime);
    }
    
    private function saveAndGenerateSettleData($partimeWorkerArr,$settleStartTime,$settleEndTime){
        foreach($partimeWorkerArr as $partimeWorker){
            $workerIdCard = $partimeWorker['worker_idcard'];
            $workerId = $partimeWorker['worker_id'];
            //订单收入明细
            //已对账的订单，且没有投诉和赔偿的订单
            $orderIncomeDetail = array(['worker_id'=>'555','order_id'=>'801','order_pay_type'=>'0','order_money'=>'50','order_booked_count'=>'2','order_complete_time'=>'1234455'],
                ['worker_id'=>'666','order_id'=>'802','order_pay_type'=>'1','order_money'=>'50','order_booked_count'=>'2','order_complete_time'=>'123456565']
            );

            $financeWorkerOrderIncomeArr = array();
            foreach($orderIncomeDetail as $orderIncome){
                $financeWorkerOrder = new FinanceWorkerOrderIncome;
                $financeWorkerOrder->worder_id = $orderIncome['worker_id'];
                $financeWorkerOrder->order_id = $orderIncome['order_id'];
                $financeWorkerOrder->finance_worker_order_income_type = $orderIncome['order_pay_type'];
                $financeWorkerOrder->finance_worker_order_income =  $orderIncome['order_money'];
                $financeWorkerOrder->order_booked_count = $orderIncome['order_booked_count'];
                $financeWorkerOrder->finance_worker_order_income_starttime = $settleStartTime;
                $financeWorkerOrder->finance_worker_order_income_endtime = $settleEndTime;
                $financeWorkerOrder->created_at = time();
                $financeWorkerOrderIncomeArr[]= $financeWorkerOrder;
            }
            //获取订单总收入
            $orderIncomeSummary = array('worker_id'=>'555','worker_idcard'=>'4210241983',
            'finance_settle_apply_man_hour'=>6,'finance_settle_apply_order_money'=>150,
            'finance_settle_apply_order_cash_money'=>0);
            $financeSettleApply = new FinanceSettleApply;
            $financeSettleApply->worder_id = $orderIncomeSummary['worker_id'];
            $financeSettleApply->worder_tel = '1380000';
            $financeSettleApply->worker_type_id = 1;
            $financeSettleApply->worker_type_name = '兼职';
            $financeSettleApply->finance_settle_apply_man_hour = $orderIncomeSummary['finance_settle_apply_man_hour'];//总工时
            $financeSettleApply->finance_settle_apply_order_money = $orderIncomeSummary['finance_settle_apply_order_money'];//订单总金额
            $financeSettleApply->finance_settle_apply_order_cash_money = $orderIncomeSummary['finance_settle_apply_order_cash_money'];//收取的现金
            $financeSettleApply->finance_settle_apply_order_money_except_cash = $orderIncomeSummary['finance_settle_apply_order_money']-$orderIncomeSummary['finance_settle_apply_order_cash_money'];//最后结算的金额
            $financeSettleApply->finance_settle_apply_subsidy = 0;
            $financeSettleApply->finance_settle_apply_money =$orderIncomeSummary['finance_settle_apply_order_money']-$orderIncomeSummary['finance_settle_apply_order_cash_money']+ $financeSettleApply->finance_settle_apply_subsidy;//应结算金额
            $financeSettleApply->finance_settle_apply_cycle = FinanceSettleApply::FINANCE_SETTLE_APPLY_CYCLE_WEEK;//结算周期
            $financeSettleApply->finance_settle_apply_cycle_des = FinanceSettleApply::FINANCE_SETTLE_APPLY_CYCLE_WEEK_DES;//结算周期描述
            $financeSettleApply->finance_settle_apply_status = FinanceSettleApply::FINANCE_SETTLE_APPLY_STATUS_INIT;//提交结算申请
            $financeSettleApply->finance_settle_apply_starttime = $settleStartTime;//结算开始日期
            $financeSettleApply->finance_settle_apply_endtime = $settleEndTime;//结算截止日期
            $financeSettleApply->created_at = time();//申请创建时间
            //获取阿姨的奖励信息
            $workerSubsidyArr = Array(['finance_worker_non_order_income_type'=>1,'finance_worker_non_order_income_type_des'=>'补贴','finance_worker_non_order_income'=>10,'finance_worker_non_order_income_des'=>'路补超过7公里，补助10元'],);
            $financeWorkerNonOrderIncomeArr = [];
            foreach($workerSubsidyArr as $workerSubsidy){
                $financeWorkerNonOrderIncome = new FinanceWorkerNonOrderIncome;
                $financeWorkerNonOrderIncome->worder_id = $workerId;
                $financeWorkerNonOrderIncome->finance_worker_non_order_income_type = $workerSubsidy['finance_worker_non_order_income_type'];
                $financeWorkerNonOrderIncome->finance_worker_non_order_income_type_des = $workerSubsidy['finance_worker_non_order_income_type_des'];
                $financeWorkerNonOrderIncome->finance_worker_non_order_income = $workerSubsidy['finance_worker_non_order_income'];
                $financeWorkerNonOrderIncome->finance_worker_non_order_income_des = $workerSubsidy['finance_worker_non_order_income_des'];
                $financeWorkerNonOrderIncome->finance_worker_non_order_income_starttime = $settleStartTime;
                $financeWorkerNonOrderIncome->finance_worker_non_order_income_endtime = $settleStartTime;
                $financeWorkerNonOrderIncome->created_at = time();
                $financeWorkerNonOrderIncomeArr[] = $financeWorkerNonOrderIncome;
            }
            $transaction =  Yii::$app->db->beginTransaction();
            try{
                $existCount = FinanceSettleApply::find()->where(['worder_id'=>$financeSettleApply->worder_id,'finance_settle_apply_starttime'=>$settleStartTime,'finance_settle_apply_endtime'=>$settleEndTime])->count();
                echo '---'.$existCount;
                if($existCount == 0){
                    if($financeSettleApply->save()){
                        foreach($financeWorkerOrderIncomeArr as $financeWorkerOrderIncome){
                            $financeWorkerOrderIncome->save();
                        }
                        foreach($financeWorkerNonOrderIncomeArr as $financeWorkerNonOrder){
                            $financeWorkerNonOrder->save();
                        }
                    }
                }else{

                }
                $transaction->commit();
            }catch(Exception $e){
                $transaction->rollBack();
            }
        }
    }
}
