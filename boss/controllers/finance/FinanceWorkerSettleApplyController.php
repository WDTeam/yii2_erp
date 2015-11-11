<?php

namespace boss\controllers\finance;

use boss\components\BaseAuthController;

use core\models\finance\FinanceWorkerSettleApplySearch;
use core\models\finance\FinanceSettleApplyApprovalLogSearch;
use core\models\finance\FinanceWorkerOrderIncomeSearch;
use core\models\finance\FinanceWorkerNonOrderIncomeSearch;
use core\models\finance\FinanceShopSettleApplySearch;
use core\models\worker\Worker;

use PHPExcel;
use PHPExcel_IOFactory;

use Yii;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;


/**
 * FinanceSettleApplyController implements the CRUD actions for FinanceSettleApply model.
 */
class FinanceWorkerSettleApplyController extends BaseAuthController
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
        $searchModel = new FinanceWorkerSettleApplySearch;
        $defaultParams = array('FinanceWorkerSettleApplySearch'=>['finance_worker_settle_apply_status' => '0']);
        $requestParams = Yii::$app->request->getQueryParams();
        $nodeId = null;
        if(isset($requestParams['FinanceWorkerSettleApplySearch'])){
            $requestModel = $requestParams['FinanceWorkerSettleApplySearch'];
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
    public function actionSelfFulltimeIndex(){
        $requestParams = Yii::$app->request->getQueryParams();
        $searchModel = $this->initQueryParams(FinanceWorkerSettleApplySearch::SELF_FULLTIME_WORKER_SETTELE,FinanceShopSettleApplySearch::BUSINESS_REVIEW,$requestParams);
        $requestPhone = $searchModel->worker_tel;
        $searchModel->worker_tel = null;
        $dataProvider = $searchModel->search(null);
        $searchModel->worker_tel = $requestPhone;
        return $this->render('selfFulltimeWorkerSettleIndex', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    /**
     * 自营兼职阿姨审核列表
     */
    public function actionSelfParttimeIndex(){
        $requestParams = Yii::$app->request->getQueryParams();
        $searchModel = $this->initQueryParams(FinanceWorkerSettleApplySearch::SELF_PARTTIME_WORKER_SETTELE,FinanceShopSettleApplySearch::BUSINESS_REVIEW,$requestParams);
        $requestPhone = $searchModel->worker_tel;
        $searchModel->worker_tel = null;
        $dataProvider = $searchModel->search(null);
        $searchModel->worker_tel = $requestPhone;
        return $this->render('selfFulltimeWorkerSettleIndex', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
     /**
     * 门店阿姨审核列表
     */
    public function actionShopWorkerIndex(){
        $requestParams = Yii::$app->request->getQueryParams();
        $searchModel = $this->initQueryParams(FinanceWorkerSettleApplySearch::SHOP_WORKER_SETTELE,FinanceShopSettleApplySearch::BUSINESS_REVIEW,$requestParams);
        $requestPhone = $searchModel->worker_tel;
        $searchModel->worker_tel = null;
        $dataProvider = $searchModel->search(null);
        $searchModel->worker_tel = $requestPhone;
        return $this->render('selfFulltimeWorkerSettleIndex', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    /**
     * 财务审核阿姨列表
     */
    public function actionFinanceSettleWorkerIndex(){
        $requestParams = Yii::$app->request->getQueryParams();
        $searchModel = $this->initQueryParams(FinanceWorkerSettleApplySearch::ALL_WORKER_SETTELE,FinanceShopSettleApplySearch::FINANCE_REVIEW,$requestParams);
        $requestPhone = $searchModel->worker_tel;
        $searchModel->worker_tel = null;
        $dataProvider = $searchModel->search(null);
        $searchModel->worker_tel = $requestPhone;
        return $this->render('selfFulltimeWorkerSettleIndex', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    private function initQueryParams($settle_type,$review_section,$requestParams){
        $searchModel = new FinanceWorkerSettleApplySearch;
        $searchModel->settle_type = $settle_type;
        $searchModel->review_section = $review_section;
        //自营全职阿姨
        if($settle_type == FinanceWorkerSettleApplySearch::SELF_FULLTIME_WORKER_SETTELE){
            $searchModel->settleMonth = date('Y-m', strtotime('-1 month'));
            $searchModel->finance_worker_settle_apply_status = FinanceWorkerSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_INIT;
            $searchModel->worker_type_id = FinanceWorkerSettleApplySearch::SELF_OPERATION;
            $searchModel->worker_identity_id = FinanceWorkerSettleApplySearch::FULLTIME;
        }
        if($settle_type == FinanceWorkerSettleApplySearch::SELF_PARTTIME_WORKER_SETTELE){
            $searchModel->finance_worker_settle_apply_status = FinanceWorkerSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_INIT;
            $searchModel->worker_type_id = FinanceWorkerSettleApplySearch::SELF_OPERATION;
            $searchModel->worker_identity_id = FinanceWorkerSettleApplySearch::PARTTIME;
        }
        if($settle_type == FinanceWorkerSettleApplySearch::SHOP_WORKER_SETTELE){
            $searchModel->finance_worker_settle_apply_status = FinanceWorkerSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_INIT;
            $searchModel->worker_type_id = FinanceWorkerSettleApplySearch::NON_SELF_OPERATION;
        }
        if($settle_type == FinanceWorkerSettleApplySearch::ALL_WORKER_SETTELE){
            $searchModel->finance_worker_settle_apply_status = FinanceWorkerSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_BUSINESS_PASSED;
        }
        $searchModel->load($requestParams);
        if(!empty($searchModel->worker_tel)){
            $searchModel->worker_id = $searchModel->getWorkerIdByWorkerTel($searchModel->worker_tel);
        }
        return $searchModel;
    }
    
    /**
     * 自营全职阿姨审核结果
     */
    public function actionSelfFulltimeWorkerSettleDone(){
        $searchModel = new FinanceWorkerSettleApplySearch;
        $requestParams = Yii::$app->request->getQueryParams();
        $id = $requestParams['id'];
        $model = $this->findModel($id);
        $review_section = $requestParams['review_section'];
        $settle_type = $requestParams['settle_type'];
        $is_ok = $requestParams['is_ok'];
        if(isset($requestParams['comment'])){
            $model->comment = $requestParams['comment'];
        }
         $isFinacePayedConfirm = false;
        if(isset($requestParams['isFinacePayedConfirm'])){
            $isFinacePayedConfirm = $requestParams['isFinacePayedConfirm'];
        }
        if($review_section== FinanceShopSettleApplySearch::BUSINESS_REVIEW){
            if($is_ok == 1){
                $model->finance_worker_settle_apply_status = FinanceWorkerSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_BUSINESS_PASSED;
            }else{
                $model->finance_worker_settle_apply_status = FinanceWorkerSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_BUSINESS_FAILED;
            }
        }elseif($review_section == FinanceShopSettleApplySearch::FINANCE_REVIEW){
            if($is_ok == 1){
                $model->finance_worker_settle_apply_status = FinanceWorkerSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_FINANCE_PASSED;
                if($isFinacePayedConfirm == true){
                    $model->finance_worker_settle_apply_status = FinanceWorkerSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_FINANCE_PAYED;
                }
            }else{
                $model->finance_worker_settle_apply_status = FinanceWorkerSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_FINANCE_FAILED;
            }
        }
        $model->updated_at = time();
        $model->save();
        $financeSettleApplyLogSearch = new FinanceSettleApplyApprovalLogSearch;
        $financeSettleApplyLogSearch->finance_settle_apply_id = $id;
        $financeSettleApplyLogSearch->finance_settle_apply_code = $model->finance_worker_settle_apply_code;
        $financeSettleApplyLogSearch->finance_settle_apply_reviewer_id = Yii::$app->user->id;
        $financeSettleApplyLogSearch->finance_settle_apply_reviewer = Yii::$app->user->identity->username;
        $financeSettleApplyLogSearch->finance_settle_apply_node_id = $review_section;
        $financeSettleApplyLogSearch->finance_settle_apply_node_des = $searchModel->financeSettleApplyStatusArr[$model->finance_worker_settle_apply_status];
        $financeSettleApplyLogSearch->finance_settle_apply_is_passed =$is_ok;
        $financeSettleApplyLogSearch->created_at = time();
        $financeSettleApplyLogSearch->save();
        return $this->actionCommonIndex($settle_type,$review_section);
    }
    
    public function actionCommonIndex($settle_type,$review_section){
        if((FinanceWorkerSettleApplySearch::SELF_FULLTIME_WORKER_SETTELE == $settle_type) && (FinanceShopSettleApplySearch::BUSINESS_REVIEW == $review_section)){
            $this->redirect('self-fulltime-index');
        }else if((FinanceWorkerSettleApplySearch::SELF_PARTTIME_WORKER_SETTELE == $settle_type) && (FinanceShopSettleApplySearch::BUSINESS_REVIEW == $review_section)){
            $this->redirect('self-parttime-index');
        }else if((FinanceWorkerSettleApplySearch::SHOP_WORKER_SETTELE == $settle_type) && (FinanceShopSettleApplySearch::BUSINESS_REVIEW == $review_section)){
            $this->redirect('shop-worker-index');
        }else if((FinanceWorkerSettleApplySearch::ALL_WORKER_SETTELE == $settle_type) && (FinanceShopSettleApplySearch::FINANCE_REVIEW == $review_section)){
            $this->redirect('finance-settle-worker-index');
        }
    }
    
    public function actionReviewFailedReason(){
        $searchModel = new FinanceWorkerSettleApplySearch;
        $requestParams = Yii::$app->request->getQueryParams();
        $searchModel = $searchModel->find()->where(['id'=>$requestParams['id']])->one();
        $settle_type = $requestParams['settle_type'];
        $searchModel->settle_type = $settle_type;
        $searchModel->review_section = $requestParams['review_section'];
        return $this->renderAjax('reviewFailedReason', [
            'model' => $searchModel,
        ]);
    }
    
    /**
     * 阿姨结算详细信息
     * @return type
     */
    public function actionSelfFulltimeWorkerSettleView(){
        $financeSettleApplySearch= new FinanceWorkerSettleApplySearch;
        $financeWorkerNonOrderIncomeSearch = new FinanceWorkerNonOrderIncomeSearch();
        $requestParams = Yii::$app->request->getQueryParams();
        $financeSettleApplySearch->load($requestParams);
        $financeSettleApplySearch = $financeSettleApplySearch->findOne(['id'=>$financeSettleApplySearch->id]);
        $financeSettleApplySearch->workerTypeDes = $financeSettleApplySearch->getWorkerTypeDes($financeSettleApplySearch->worker_type_id,$financeSettleApplySearch->worker_identity_id);
        $financeWorkerOrderIncomeSearch = new FinanceWorkerOrderIncomeSearch;
        $financeWorkerOrderIncomeSearch->load($requestParams);
        if(isset($requestParams['finance_worker_order_income_type'])){
            $financeWorkerOrderIncomeSearch->finance_worker_order_income_type = $requestParams['finance_worker_order_income_type'];
        }
        $orderDataProvider = new ArrayDataProvider();
        $cashOrderDataProvider = new ArrayDataProvider();
        $taskDataProvider = new ArrayDataProvider();
        $compensateDataProvider = new ArrayDataProvider();
        //获取结算模块保存的订单流水信息
        $orderDataProvider = $financeWorkerOrderIncomeSearch->getOrderDataProviderBySettleId($financeSettleApplySearch->id);
        //获取结算模块保存的现金订单流水信息
        $cashOrderDataProvider = $financeWorkerOrderIncomeSearch->getCashOrderDataProviderBySettleId($financeSettleApplySearch->id);
        //获取结算模块保存的任务奖励信息
        $taskDataProvider = $financeWorkerNonOrderIncomeSearch->getTaskDataProviderBySettleId($financeSettleApplySearch->id);
        //获取结算模块保存的赔偿信息
        $compensateDataProvider = $financeWorkerNonOrderIncomeSearch->getCompensateDataProviderBySettleId($financeSettleApplySearch->worker_id, null, null);
        return $this->render('selfFulltimeWorkerSettleView', ['model'=>$financeSettleApplySearch,'orderDataProvider'=>$orderDataProvider,'cashOrderDataProvider'=>$cashOrderDataProvider,'taskDataProvider'=>$taskDataProvider,'compensateDataProvider'=>$compensateDataProvider]);
    }
    
    
    /**
     * Lists all FinanceSettleApply models.
     * @return mixed
     */
    public function actionQuery()
    {
        $financeSearchModel = new FinanceWorkerSettleApplySearch;
        $requestParams = Yii::$app->request->getQueryParams();
        $financeSearchModel->scenario = 'query';
        $isExport = 0;
        if(isset($requestParams['isExport'])){
            $isExport = $requestParams['isExport'];
        }
        if(\Yii::$app->user->identity->isMiniBossUser()){
            $financeSearchModel->shop_id = Yii::$app->user->identity->getShopIds();
            $financeSearchModel->shop_manager_id = Yii::$app->user->identity->getShopManagerIds();
        }
        $financeSearchModel->settle_apply_create_start_time = FinanceWorkerSettleApplySearch::getFirstDayOfSpecifiedMonth();
        $financeSearchModel->settle_apply_create_end_time = FinanceWorkerSettleApplySearch::getLastDayOfSpecifiedMonth();
        $financeSearchModel->load($requestParams);
        if(!empty($financeSearchModel->worker_tel)){
            $financeSearchModel->worker_id = $financeSearchModel->getWorkerIdByWorkerTel($financeSearchModel->worker_tel);
        }
        $requestPhone = $financeSearchModel->worker_tel;
        $financeSearchModel->worker_tel = null;
        $dataProvider = $financeSearchModel->search(null);
        if($isExport == 0){
            $financeSearchModel->worker_tel = $requestPhone;
            return $this->render('query', [
                'dataProvider' => $dataProvider,
                'searchModel' => $financeSearchModel,
            ]);
        }
        if($isExport == 1){
            $this->export($dataProvider->query->all());
        }
    }
    
    /**
     * 导出提交oa系统需要的excel表
     * 1.动态组装excel标题，例如"通州门店8月全时段家政人员订单费用表"
     * 2.动态组装表头信息，例如：‘店面’、‘姓名’、‘扣款’，‘阿姨所得’等
     * 3.写入每个阿姨实时的收入数据
     * @return type
     */
    public function export($financeSettleApplySearchArray){
        $exportArray = [];
        $i = 0;
        foreach($financeSettleApplySearchArray as $financeSettleApplySearch){
            $exportRow = [];
            $worker_id = $financeSettleApplySearch->worker_id;
            $workerBankInfo = Worker::getWorkerBankInfo($worker_id);
            if(count($workerBankInfo) > 0){
                $exportRow['receiver'] = $financeSettleApplySearch->worker_name;//收款人
                $exportRow['receiver_bank_account'] = $workerBankInfo[0]['worker_bank_card'];//收款人账号
                $exportRow['receiver_bank_name'] = $workerBankInfo[0]['worker_bank_name'];//开户行
                $exportRow['receiver_bank_branch'] = $workerBankInfo[0]['worker_bank_from'];//开户网点
                $exportRow['receiver_bank_address'] = $workerBankInfo[0]['worker_bank_area'];//开户地址
                $exportRow['receiver_income'] = $financeSettleApplySearch->finance_worker_settle_apply_money;//打款金额
            }
            $exportArray[$i] = $exportRow;
            $i++;
        }
        $objPHPExcel=new PHPExcel();
        $objPHPExcel->getProperties()->setCreator('ejiajie')
                ->setLastModifiedBy('ejiajie')
                ->setTitle('Office 2007 XLSX Document')
                ->setSubject('Office 2007 XLSX Document')
                ->setDescription('Document for Office 2007 XLSX, generated using PHP classes.')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('Result file');
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1','收款人')
                    ->setCellValue('B1','收款人账号')
                    ->setCellValue('C1','金额')
                    ->setCellValue('D1','开户行')
                    ->setCellValue('E1','开户网点')
                    ->setCellValue('F1','开户地址');
        $i=2;   
        foreach($exportArray as $k=>$v){
         $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i,$v['receiver'])
                    ->setCellValue('B'.$i,$v['receiver_bank_account'])
                    ->setCellValue('C'.$i,$v['receiver_income'])
                    ->setCellValue('D'.$i,$v['receiver_bank_name'])
                    ->setCellValue('E'.$i,$v['receiver_bank_branch'])
                    ->setCellValue('F'.$i,$v['receiver_bank_address']);
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
        
        $searchModel = new FinanceWorkerSettleApplySearch;
        $requestModel = Yii::$app->request->getQueryParams();
        $financeSettleApplySearch = $requestModel["FinanceWorkerSettleApplySearch"];
        //结算id字符串，例如："234,345"
        $ids = $financeSettleApplySearch["ids"];
        $financeSettleApplyStatus = $financeSettleApplySearch["finance_worker_settle_apply_status"];
        $idArr = explode(',', $ids);
        
        foreach($idArr as $id){
            $model = $this->findModel($id);
            $model->finance_worker_settle_apply_status = $financeSettleApplyStatus;
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
        $model = new FinanceWorkerSettleApplySearch;

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
        if (($model = FinanceWorkerSettleApplySearch::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
    * 阿姨人工结算详细信息
    */
    public function actionWorkerManualSettlementIndex(){
        $financeSettleApplySearch= new FinanceWorkerSettleApplySearch;
        $financeSettleApplySearch->scenario = 'count';
        $financeWorkerNonOrderIncomeSearch = new FinanceWorkerNonOrderIncomeSearch();
        $requestParams = Yii::$app->request->getQueryParams();
        $review_section = $requestParams['review_section'];
        $settle_type = $requestParams['settle_type'];
        $financeSettleApplySearch->load($requestParams);
        if($settle_type == FinanceWorkerSettleApplySearch::SHOP_WORKER_SETTELE){
            $worker_type = FinanceWorkerSettleApplySearch::NON_SELF_OPERATION;
        }else{
            $worker_type = FinanceWorkerSettleApplySearch::SELF_OPERATION;
        }
        $orderDataProvider = new ArrayDataProvider();
        $cashOrderDataProvider = new ArrayDataProvider();
        $taskDataProvider = new ArrayDataProvider();
        $compensateDataProvider = new ArrayDataProvider();
        if(!empty($financeSettleApplySearch->worker_tel)){
            if(!$financeSettleApplySearch->isWorkerExist($financeSettleApplySearch->worker_tel, $worker_type)){
                 Yii::$app->getSession()->setFlash('default', "未找到该阿姨的信息，请确认阿姨类型");
                 $financeSettleApplySearch->worker_tel = "";
            }else{
                $financeSettleApplySearch->getWorkerInfo($financeSettleApplySearch->worker_tel);//获取阿姨的信息
                $financeSettleApplySearch->settle_type = $settle_type;
                $financeSettleApplySearch->review_section = $review_section;
                $workerInfo = Worker::getWorkerInfo($financeSettleApplySearch->worker_id);
                $finance_worker_settle_apply_starttime = null;
                $finance_worker_settle_apply_endtime = null;
                if(count($workerInfo)>0){
                    $worker_type_id = $workerInfo['worker_type'];
                    $worker_identity_id = $workerInfo['worker_identity_id'];
                    if(($worker_type_id ==FinanceWorkerSettleApplySearch::SELF_OPERATION ) && ($worker_identity_id == FinanceWorkerSettleApplySearch::FULLTIME)){
                        $finance_worker_settle_apply_starttime = FinanceWorkerSettleApplySearch::getFirstDayOfSpecifiedMonth();//结算开始日期
                        $finance_worker_settle_apply_endtime = FinanceWorkerSettleApplySearch::getLastDayOfSpecifiedMonth();//结算截止日期
                    }else{
                        $finance_worker_settle_apply_starttime = FinanceWorkerSettleApplySearch::getFirstDayOfLastWeek();//结算开始日期
                        $finance_worker_settle_apply_endtime = FinanceWorkerSettleApplySearch::getLastDayOfLastWeek();//结算截止日期
                    }
                }
                $existCount = FinanceWorkerSettleApplySearch::find()->where(['worker_id'=>$financeSettleApplySearch->worker_id,'finance_worker_settle_apply_starttime'=>$finance_worker_settle_apply_starttime,'finance_worker_settle_apply_endtime'=>$finance_worker_settle_apply_endtime])->count();
                if($existCount > 0){
                    Yii::$app->getSession()->setFlash('default', "该阿姨已经生成本周期的结算单");
                    $financeSettleApplySearch->worker_tel = "";
                }else{
                    $financeSettleApplySearch = $financeSettleApplySearch->getWorkerSettlementSummaryInfo($financeSettleApplySearch->worker_id,$finance_worker_settle_apply_starttime,$finance_worker_settle_apply_endtime);
                    $financeWorkerOrderIncomeSearch = new FinanceWorkerOrderIncomeSearch;
                    //订单详细数据
                    $orderDataProvider = $financeWorkerOrderIncomeSearch->getOrderDataProviderFromOrder($financeSettleApplySearch->worker_id,$finance_worker_settle_apply_starttime,$finance_worker_settle_apply_endtime);
                    //现金订单详细数据
                    $cashOrderDataProvider = $financeWorkerOrderIncomeSearch->getCashOrderDataProviderFromOrder($financeSettleApplySearch->worker_id,$finance_worker_settle_apply_starttime,$finance_worker_settle_apply_endtime);
                    //任务详细数据
                    $taskDataProvider = $financeWorkerNonOrderIncomeSearch->getTaskDataProviderByWorkerId($financeSettleApplySearch->worker_id, $finance_worker_settle_apply_starttime,$finance_worker_settle_apply_endtime);
                    //赔偿详细数据
                    $compensateDataProvider = $financeWorkerNonOrderIncomeSearch->getCompensateDataProviderByWorkerId($financeSettleApplySearch->worker_id, $finance_worker_settle_apply_starttime, $finance_worker_settle_apply_endtime);
                }
            }
        }
        
        return $this->render('workerManualSettlementIndex', ['model'=>$financeSettleApplySearch,'orderDataProvider'=>$orderDataProvider,'cashOrderDataProvider'=>$cashOrderDataProvider,'taskDataProvider'=>$taskDataProvider,'compensateDataProvider'=>$compensateDataProvider]);
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
        $workerInfo = Worker::getWorkerInfo($worker_id);
        $finance_worker_settle_apply_starttime = null;
        $finance_worker_settle_apply_endtime = null;
        if(count($workerInfo)>0){
            $worker_type_id = $workerInfo['worker_type'];
            $worker_identity_id = $workerInfo['worker_identity_id'];
            if(($worker_type_id ==FinanceWorkerSettleApplySearch::SELF_OPERATION ) && ($worker_identity_id == FinanceWorkerSettleApplySearch::FULLTIME)){
                $finance_worker_settle_apply_starttime = FinanceWorkerSettleApplySearch::getFirstDayOfSpecifiedMonth();//结算开始日期
                $finance_worker_settle_apply_endtime = FinanceWorkerSettleApplySearch::getLastDayOfSpecifiedMonth();//结算截止日期
            }else{
                $finance_worker_settle_apply_starttime = FinanceWorkerSettleApplySearch::getFirstDayOfLastWeek();//结算开始日期
                $finance_worker_settle_apply_endtime = FinanceWorkerSettleApplySearch::getLastDayOfLastWeek();//结算截止日期
            }
        }
        $this->saveAndGenerateSettleData($partimeWorkerArr,$finance_worker_settle_apply_starttime,$finance_worker_settle_apply_endtime);
        
        return $this->actionCommonIndex($settle_type,$review_section);
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
    public function actionSelfParttimeWorkerCycleSettlement(){
        $settleStartTime = FinanceWorkerSettleApplySearch::getFirstDayOfLastWeek();//统计开始时间,上周第一天
        echo date('Y-m-d 00:00:00', strtotime('-1 week last monday')).'------';
        $settleEndTime = FinanceWorkerSettleApplySearch::getLastDayOfLastWeek();//统计结束时间,上周最后一天
        echo date('Y-m-d 23:59:59', strtotime('last sunday')).'------';
        //自营兼职阿姨的结算
        $selfPartimeWorkerArr = Worker::getWorkerIds(FinanceWorkerSettleApplySearch::SELF_OPERATION, FinanceWorkerSettleApplySearch::PARTTIME);
        $this->saveAndGenerateSettleData($selfPartimeWorkerArr,$settleStartTime,$settleEndTime);
        //门店阿姨的结算
        $nonSelfWorkerArr = Worker::getWorkerIds(FinanceWorkerSettleApplySearch::NON_SELF_OPERATION, null);
        $this->saveAndGenerateSettleData($nonSelfWorkerArr,$settleStartTime,$settleEndTime);
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
    public function actionSelfFulltimeWorkerCycleSettlement(){
        $settleStartTime = FinanceWorkerSettleApplySearch::getFirstDayOfSpecifiedMonth();//统计开始时间,上个月的第一天
        echo date('Y-m-01 00:00:00', strtotime('-1 month')).'------';
        $settleEndTime = FinanceWorkerSettleApplySearch::getLastDayOfSpecifiedMonth();//统计结束时间,上个月的最后一天
        echo date('Y-m-t 23:59:59', strtotime('-1 month')).'------';
        //获取所有"自营全职"得阿姨的Id列表,不考虑以下状态：拉黑、封号、离职、请假、休假
        $partimeWorkerArr = Worker::getWorkerIds(FinanceWorkerSettleApplySearch::SELF_OPERATION, FinanceWorkerSettleApplySearch::FULLTIME);
        $this->saveAndGenerateSettleData($partimeWorkerArr,$settleStartTime,$settleEndTime);
    }
    
    
    
    
    private function saveAndGenerateSettleData($workerArr,$settleStartTime,$settleEndTime){
        $financeSettleApplySearch = new FinanceWorkerSettleApplySearch();
        $financeSettleApplySearch->scenario = 'save';
        $financeWorkerOrderIncomeSearch = new FinanceWorkerOrderIncomeSearch();
        $financeWorkerNonOrderIncomeSearch = new FinanceWorkerNonOrderIncomeSearch();
        foreach($workerArr as $worker){
            //根据阿姨Id获取阿姨信息
            $workerId = $worker['worker_id'];
            //订单收入明细
            //已对账的订单，且没有未处理完的投诉和赔偿的订单
            $financeWorkerOrderIncomeArr = $financeWorkerOrderIncomeSearch->getWorkerOrderIncomeArrayByWorkerId($workerId,$settleStartTime,$settleEndTime);
            //获取订单总收入
            $financeSettleApplySearch = $financeSettleApplySearch->getWorkerSettlementSummaryInfo($workerId,$settleStartTime,$settleEndTime);
            //获取阿姨的任务奖励信息
            $financeWorkerNonOrderTaskIncomeArr = $financeWorkerNonOrderIncomeSearch->getTaskArrByWorkerId($workerId, $settleStartTime, $settleEndTime);
            $financeWorkerNonOrderCompensateIncomeArr = $financeWorkerNonOrderIncomeSearch->getCompensateArrByWorkerId($workerId, $settleStartTime, $settleEndTime);
            $transaction =  Yii::$app->db->beginTransaction();
            try{
                $existCount = FinanceWorkerSettleApplySearch::find()->where(['worker_id'=>$financeSettleApplySearch->worker_id,'finance_worker_settle_apply_starttime'=>$settleStartTime,'finance_worker_settle_apply_endtime'=>$settleEndTime])->count();
                if($existCount == 0){
                    if($financeSettleApplySearch->save()){
                        foreach($financeWorkerOrderIncomeArr as $financeWorkerOrderIncome){
                            $financeWorkerOrderIncome->finance_worker_settle_apply_id = $financeSettleApplySearch->id;
                            $financeWorkerOrderIncome->save();
                        }
                        foreach($financeWorkerNonOrderTaskIncomeArr as $financeWorkerNonOrderTask){
                            $financeWorkerNonOrderTask->finance_worker_settle_apply_id = $financeSettleApplySearch->id;
                            $saveSuccess = $financeWorkerNonOrderTask->save();
                        }
                        foreach($financeWorkerNonOrderCompensateIncomeArr as $financeWorkerNonOrderCompensateIncome){
                            $financeWorkerNonOrderCompensateIncome->finance_worker_settle_apply_id = $financeSettleApplySearch->id;
                            $saveSuccess = $financeWorkerNonOrderCompensateIncome->save();
                        }
                    }
                }
                $transaction->commit();
            }catch(Exception $e){
                $transaction->rollBack();
            }
        }
    }
}
