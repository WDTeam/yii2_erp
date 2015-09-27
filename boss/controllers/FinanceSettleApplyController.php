<?php

namespace boss\controllers;

use Yii;
use common\models\FinanceSettleApply;
use boss\models\FinanceSettleApplySearch;
use boss\models\FinanceSettleApplyLogSearch;
use boss\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\FinanceWorkerOrderIncome;
use common\models\FinanceWorkerNonOrderIncome;
use boss\models\WorkerSearch;
use boss\models\financesettle\SettleWorkerInfo;

/**
 * FinanceSettleApplyController implements the CRUD actions for FinanceSettleApply model.
 */
class FinanceSettleApplyController extends Controller
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
        $financeSettleApplySearch->workerName = "张三";
        $financeSettleApplySearch->workerPhone= "13456789000";
        $financeSettleApplySearch->workerOnboardTime= "1443324337";
        $financeSettleApplySearch->workerType= "全职全日";
        return $this->render('workerManualSettlementIndex', ['model'=>$financeSettleApplySearch,]);
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
