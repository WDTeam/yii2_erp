<?php

namespace boss\controllers\finance;
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
use boss\models\FinanceWorkerOrderIncomeSearch;
use boss\models\FinanceWorkerNonOrderIncomeSearch;
use boss\models\FinanceShopSettleApplySearch;
use PHPExcel;
use PHPExcel_IOFactory;


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
     * 自营全职阿姨审核列表
     */
    public function actionSelfFulltimeWorkerSettleIndex(){
        $requestParams = Yii::$app->request->getQueryParams();
        $searchModel = $this->initQueryParams($requestParams);
        $searchModel->load($requestParams);
        $dataProvider = $searchModel->search(['FinanceSettleApplySearch'=>$requestParams]);
        
        return $this->render('selfFulltimeWorkerSettleIndex', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    private function initQueryParams($requestParams){
        $searchModel = new FinanceSettleApplySearch;
        $settle_type = $requestParams['settle_type'];
        $searchModel->settle_type = $settle_type;
        $searchModel->review_section = $requestParams['review_section'];
        //自营全职阿姨
        if($settle_type == FinanceSettleApplySearch::SELF_FULLTIME_WORKER_SETTELE){
            $searchModel->settleMonth = date('Y-m', strtotime('-1 month'));
            $searchModel->finance_settle_apply_status = FinanceSettleApply::FINANCE_SETTLE_APPLY_STATUS_INIT;
            $searchModel->worker_type_id = 1;
            $searchModel->worker_rule_id = 1;
//            $searchModel->finance_settle_apply_starttime = $this->getFirstDayOfSpecifiedMonth();
//            $searchModel->finance_settle_apply_endtime = $this->getLastDayOfSpecifiedMonth();
            if(isset($requestParams['FinanceSettleApplySearch'])){
                $requestModel = $requestParams['FinanceSettleApplySearch'];
                if(isset($requestModel['worder_tel'])){
                    $searchModel->worder_tel = $requestModel['worder_tel'];
                }
//                if(isset($requestModel['settleMonth'])){
//                    $searchModel->settleMonth = $requestModel['settleMonth'];
//                    $searchModel->finance_settle_apply_starttime = $this->getFirstDayOfSpecifiedMonth($searchModel->settleMonth);
//                    $searchModel->finance_settle_apply_endtime = $this->getLastDayOfSpecifiedMonth($searchModel->settleMonth);
//                }
            }
        }
        if($settle_type == FinanceSettleApplySearch::SELF_PARTTIME_WORKER_SETTELE){
            $searchModel->finance_settle_apply_status = FinanceSettleApply::FINANCE_SETTLE_APPLY_STATUS_INIT;
            $searchModel->worker_type_id = 1;
            $searchModel->worker_rule_id = 2;
//            $searchModel->finance_settle_apply_starttime = strtotime('-1 week last monday');
//            $searchModel->finance_settle_apply_endtime = strtotime('last sunday');
        }
        if($settle_type == FinanceSettleApplySearch::SHOP_WORKER_SETTELE){
            $searchModel->finance_settle_apply_status = FinanceSettleApply::FINANCE_SETTLE_APPLY_STATUS_INIT;
            $searchModel->worker_type_id = 2;
//            $searchModel->finance_settle_apply_starttime = strtotime('-1 week last monday');
//            $searchModel->finance_settle_apply_endtime = strtotime('last sunday');
        }
        if($settle_type == FinanceSettleApplySearch::ALL_WORKER_SETTELE){
            $searchModel->finance_settle_apply_status = FinanceSettleApply::FINANCE_SETTLE_APPLY_STATUS_BUSINESS_PASSED;
        }
        return $searchModel;
    }
    
    /**
     * 自营全职阿姨审核结果
     */
    public function actionSelfFulltimeWorkerSettleDone(){
        $searchModel = new FinanceSettleApplySearch;
        $requestParams = Yii::$app->request->getQueryParams();
        $id = $requestParams['id'];
        $model = $this->findModel($id);
        $review_section = $requestParams['review_section'];
        $settle_type = $requestParams['settle_type'];
        $is_ok = $requestParams['is_ok'];
        if(isset($requestParams['comment'])){
            $model->comment = $requestParams['comment'];
        }
        if($review_section== FinanceShopSettleApplySearch::BUSINESS_REVIEW){
            if($is_ok == 1){
                $model->finance_settle_apply_status = FinanceSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_BUSINESS_PASSED;
            }else{
                $model->finance_settle_apply_status = FinanceSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_BUSINESS_FAILED;
            }
        }elseif($review_section == FinanceShopSettleApplySearch::FINANCE_REVIEW){
            if($is_ok == 1){
                $model->finance_settle_apply_status = FinanceSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_FINANCE_PASSED;
            }else{
                $model->finance_settle_apply_status = FinanceSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_FINANCE_FAILED;
            }
        }
        $model->save();
        $financeSettleApplyLogSearch = new FinanceSettleApplyLogSearch;
        $financeSettleApplyLogSearch->finance_settle_apply_id = $id;
        $financeSettleApplyLogSearch->finance_settle_apply_reviewer_id = Yii::$app->user->id;
        $financeSettleApplyLogSearch->finance_settle_apply_reviewer = Yii::$app->user->identity->username;
        $financeSettleApplyLogSearch->finance_settle_apply_node_id = $review_section;
        $financeSettleApplyLogSearch->finance_settle_apply_node_des = $searchModel->financeSettleApplyStatusArr[$model->finance_settle_apply_status];
        $financeSettleApplyLogSearch->finance_settle_apply_is_passed =$is_ok;
        $financeSettleApplyLogSearch->finance_settle_apply_reviewer_comment ="";
        $financeSettleApplyLogSearch->created_at = time();
        $financeSettleApplyLogSearch->save();
        return $this->redirect('self-fulltime-worker-settle-index?settle_type='.$settle_type.'&review_section='.$review_section);
    }
    
    public function actionReviewFailedReason(){
        $searchModel = new FinanceSettleApplySearch;
        $requestParams = Yii::$app->request->getQueryParams();
        $settle_type = $requestParams['settle_type'];
        $searchModel->settle_type = $settle_type;
        $searchModel->review_section = $requestParams['review_section'];
        $searchModel->id = $requestParams['id'];
        return $this->renderAjax('reviewFailedReason', [
            'model' => $searchModel,
        ]);
    }
    
    /**
     * 自营全职阿姨详细信息
     * @return type
     */
    public function actionSelfFulltimeWorkerSettleView(){
        $financeSettleApplySearch= new FinanceSettleApplySearch;
        $requestParams = Yii::$app->request->getQueryParams();
        $financeSettleApplySearch->load($requestParams);
        $financeSettleApplySearch = $financeSettleApplySearch->findOne(['id'=>$financeSettleApplySearch->id]);
        $financeSettleApplySearch->workerTypeDes = $financeSettleApplySearch->getWorkerTypeDes();
        $financeWorkerOrderIncomeSearch = new FinanceWorkerOrderIncomeSearch;
        $financeWorkerOrderIncomeSearch->load($requestParams);
        if(isset($requestParams['finance_worker_order_income_type'])){
            $financeWorkerOrderIncomeSearch->finance_worker_order_income_type = $requestParams['finance_worker_order_income_type'];
        }
        $orderDataProvider = $financeWorkerOrderIncomeSearch->getOrderDataProvider($financeSettleApplySearch->worder_id);
        return $this->render('selfFulltimeWorkerSettleView', ['model'=>$financeSettleApplySearch,'orderDataProvider'=>$orderDataProvider]);
    }
    
    
    /**
     * Lists all FinanceSettleApply models.
     * @return mixed
     */
    public function actionQuery()
    {
        $financeSearchModel = new FinanceSettleApplySearch;
        $dataProvider = $financeSearchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('query', [
            'dataProvider' => $dataProvider,
            'searchModel' => $financeSearchModel,
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
           $objPHPExcel=new PHPExcel();
           $objPHPExcel->getProperties()->setCreator('ejiajie')
                   ->setLastModifiedBy('ejiajie')
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
           $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
           ob_end_clean();
           header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
            exit;
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
    * 阿姨人工结算详细信息
    */
    public function actionWorkerManualSettlementIndex(){
        $financeSettleApplySearch= new FinanceSettleApplySearch;
        $requestParams = Yii::$app->request->getQueryParams();
        $review_section = $requestParams['review_section'];
        $settle_type = $requestParams['settle_type'];
        $financeSettleApplySearch->load($requestParams);
        if($settle_type == FinanceSettleApplySearch::SHOP_WORKER_SETTELE){
            $worker_type = FinanceSettleApplySearch::NON_SELF_OPERATION;
        }else{
            $worker_type = FinanceSettleApplySearch::SELF_OPERATION;
        }
        if(!empty($financeSettleApplySearch->worder_tel) && !$financeSettleApplySearch->isWorkerExist($financeSettleApplySearch->worder_tel, $worker_type)){
            Yii::$app->getSession()->setFlash('default', "未找到该阿姨的信息，请确认阿姨类型");
            $financeSettleApplySearch->worder_tel = "";
        }
        $financeSettleApplySearch->getWorkerInfo($financeSettleApplySearch->worder_tel);//获取阿姨的信息
        //如果是门店阿姨结算，阿姨角色肯定是
        $financeSettleApplySearch->settle_type = $settle_type;
        $financeSettleApplySearch->review_section = $review_section;
        $financeSettleApplySearch = $financeSettleApplySearch->getWorkerSettlementSummaryInfo($financeSettleApplySearch->worder_id);
        $financeWorkerOrderIncomeSearch = new FinanceWorkerOrderIncomeSearch;
        $financeWorkerOrderIncomeSearch->load($requestParams);
        if(isset($requestParams['finance_worker_order_income_type'])){
            $financeWorkerOrderIncomeSearch->finance_worker_order_income_type = $requestParams['finance_worker_order_income_type'];
        }
        $orderDataProvider = $financeWorkerOrderIncomeSearch->getOrderDataProviderFromOrder($financeSettleApplySearch->worder_id);
        $cashOrderDataProvider = $financeWorkerOrderIncomeSearch->getCashOrderDataProviderFromOrder($financeSettleApplySearch->worder_id);
        return $this->render('workerManualSettlementIndex', ['model'=>$financeSettleApplySearch,'orderDataProvider'=>$orderDataProvider,'cashOrderDataProvider'=>$cashOrderDataProvider]);
    }
    
    /**
     * 提交阿姨人工结算
     * @return type
     */
    public function actionWorkerManualSettlementDone(){
        $requestParams = Yii::$app->request->getQueryParams();
        $review_section = $requestParams['review_section'];
        $settle_type = $requestParams['settle_type'];
        $worker_id = $requestParams['worker_id'];
        $partimeWorkerArr = [['worker_id'=>$worker_id],];
        $this->saveAndGenerateSettleData($partimeWorkerArr,time(),time());
        return $this->redirect('self-fulltime-worker-settle-index?settle_type='.$settle_type.'&review_section='.$review_section);
    }
    
    /**
    * 小家政人工结算
    */
    public function actionHomemakingManualSettlementIndex(){
        $financeSettleApplySearch= new FinanceSettleApplySearch;
        $requestModel = Yii::$app->request->getQueryParams();
        if(isset($requestModel["FinanceSettleApplySearch"])){
            $financeSettleApplySearch = $requestModel["FinanceSettleApplySearch"];
        }
//        $financeSettleApplySearch = $financeSettleApplySearch->getWorkerInfo($workerId);//获取阿姨的信息
        $searchModel = new FinanceWorkerOrderIncomeSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('homemakingManualSettlementIndex', ['model'=>$financeSettleApplySearch,'dataProvider'=>$dataProvider]);
    }
    
    
    
    /**
    * 本文件是用于自营兼职阿姨每周（例如：2015.9.21-2015.9.27）的结算
    * 1.获取当前在职的且是兼职的阿姨Id列表
    * 2.获取每个阿姨的身份证号，在2015.9.21-2015.9.27已完成的订单信息，
    *   包括订单Id、支付类型、订单金额、服务时长、服务完成的时间，
    *   保存到阿姨订单收入表
    * 3.获取每个阿姨Id，在2015.9.21-2015.9.27已完成订单的总收入信息，
    *   按收入类型分组的订单总金额，保存到结算表
    */
    public function actionSelfPartTimeWorkerCycleSettlement(){
        $settleStartTime = date('Y-m-d 00:00:00', strtotime('-1 week last monday'));;//统计开始时间,上周第一天
        echo $settleStartTime.'------';
        $settleEndTime = date('Y-m-d 23:59:59', strtotime('last sunday'));//统计结束时间,上周最后一天
        echo $settleEndTime.'------';
        //获取阿姨的数组信息
        $partimeWorkerArr = array(['worker_id'=>'555','worker_name'=>'阿姨1','worker_idcard'=>'4210241983','worker_bank_card'=>'62217978'],['worker_id'=>'666','worker_name'=>'阿姨2','worker_idcard'=>'4210241984','worker_bank_card'=>'622174747']);
        $this->saveAndGenerateSettleData($partimeWorkerArr,$settleStartTime,$settleEndTime);
    }
    
    /**
    * 本文件是用于自营全职阿姨每月（例如：2015.9.1-2015.9.30）的结算
    * 1.获取当前在职的且是全职的阿姨Id列表
    * 2.获取每个阿姨，在2015.9.1-2015.9.30已完成的订单信息，
    *   包括订单Id、支付类型、订单金额、服务时长、服务完成的时间，
    *   保存到阿姨订单收入表
    * 3.获取每个阿姨Id，在2015.9.1-2015.9.30已完成订单的总收入信息，
    *   按收入类型分组的订单总金额，保存到结算表
    */
    public function actionSelfFullTimeWorkerCycleSettlement(){
        $settleStartTime = $this->getFirstDayOfSpecifiedMonth();//统计开始时间,上个月的第一天
        echo date('Y-m-01 00:00:00', strtotime('-1 month')).'------';
        $settleEndTime = $this->getLastDayOfSpecifiedMonth();//统计结束时间,上个月的最后一天
        echo date('Y-m-t 23:59:59', strtotime('-1 month')).'------';
        //获取所有"自营全职"得阿姨的Id列表,不考虑以下状态：拉黑、封号、离职、请假、休假
        $partimeWorkerArr = array(['worker_id'=>'555','worker_name'=>'阿姨1','worker_idcard'=>'4210241983','worker_bank_card'=>'62217978'],['worker_id'=>'666','worker_name'=>'阿姨2','worker_idcard'=>'4210241984','worker_bank_card'=>'622174747']);
        $this->saveAndGenerateSettleData($partimeWorkerArr,$settleStartTime,$settleEndTime);
    }
    
    
    /**
     * 获取指定月份的第一天
     * @return type
     */
    private function getFirstDayOfSpecifiedMonth($yearAndMonth = null){
        if($yearAndMonth == null){
            $yearAndMonth = date('Y-m', strtotime('-1 month'));
        }
        return strtotime(date('Y-m-01 00:00:00', strtotime($yearAndMonth)));
    }
    
    /**
     * 获取指定月份的最后一天
     */
    private function getLastDayOfSpecifiedMonth($yearAndMonth = null){
        if($yearAndMonth == null){
            $yearAndMonth = date('Y-m', strtotime('-1 month'));
        }
        return strtotime(date('Y-m-t 23:59:59', strtotime($yearAndMonth)));
    }
    
    private function saveAndGenerateSettleData($workerArr,$settleStartTime,$settleEndTime){
        $financeSettleApplySearch = new FinanceSettleApplySearch();
        foreach($workerArr as $worker){
            //根据阿姨Id获取阿姨信息
            $workerId = $worker['worker_id'];
            //订单收入明细
            //已对账的订单，且没有投诉和赔偿的订单
            $orderIncomeDetail = $financeSettleApplySearch->getWorkerOrderInfo($workerId);

            $financeWorkerOrderIncomeArr = array();
            foreach($orderIncomeDetail as $orderIncome){
                $financeWorkerOrder = new FinanceWorkerOrderIncome;
                $financeWorkerOrder->worder_id = $workerId;
                $financeWorkerOrder->order_id = $orderIncome['id'];
                $financeWorkerOrder->finance_worker_order_income_type = $orderIncome->orderExtPay->order_pay_type;
                $financeWorkerOrder->finance_worker_order_income =  $orderIncome['order_money'];
                $financeWorkerOrder->order_booked_count = $orderIncome['order_booked_count'];
                $financeWorkerOrder->finance_worker_order_income_starttime = $settleStartTime;
                $financeWorkerOrder->finance_worker_order_income_endtime = $settleEndTime;
                $financeWorkerOrder->created_at = time();
                $financeWorkerOrderIncomeArr[]= $financeWorkerOrder;
            }
            //获取订单总收入
            $financeSettleApplySearch = $financeSettleApplySearch->getWorkerSettlementSummaryInfo($workerId);

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
                $existCount = FinanceSettleApply::find()->where(['worder_id'=>$financeSettleApplySearch->worder_id,'finance_settle_apply_starttime'=>$settleStartTime,'finance_settle_apply_endtime'=>$settleEndTime])->count();
                echo '---'.$existCount;
                if($existCount == 0){
                    if($financeSettleApplySearch->save()){
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
