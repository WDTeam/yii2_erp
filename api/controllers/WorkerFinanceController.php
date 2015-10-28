<?php
namespace api\controllers;

use Yii;
use core\models\finance\FinanceSettleApplySearch;
/**
 * Description of WorkerFinanceController
 *
 * @author weibeinan
 */
class WorkerFinanceController extends \api\components\Controller{
    //put your code here
    
    public function actionGetWorkerIncomeSummaryInfo(){
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
//        if (!isset($param['access_token']) || !$param['access_token'] || !isset($param['worker_id']) || !$param['worker_id'] || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
//            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
//        }
        // 按阿姨id获取阿姨信息
        $workerId = intval($param['worker_id']);
        if (!empty($workerId) ) {
            $workerSummaryInfo = FinanceSettleApplySearch::getWorkerIncomeSummaryInfoByWorkerId($workerId);
            if(!empty($workerSummaryInfo)){
                return $this->send($workerSummaryInfo, "阿姨收入信息查询成功");
            }
        }
    }
    
    public function actionGetWorkerIncomeList(){
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        // 按阿姨id获取阿姨信息
        $workerId = intval($param['worker_id']);
        if (!empty($workerId) ) {
            $workerIncomeList = FinanceSettleApplySearch::getSettledWorkerIncomeListByWorkerId($workerId,1,5);
            if(!empty($workerIncomeList)){
                return $this->send($workerIncomeList, "阿姨收入信息查询成功");
            }
        }
    }
    
    public function actionGetWorkerOrderIncomeList(){
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        // 按阿姨id获取阿姨信息
        $settle_id = intval($param['settle_id']);
        if (!empty($settle_id) ) {
            $orderArray = FinanceSettleApplySearch::getOrderArrayBySettleId($settle_id);
            if(!empty($orderArray) && (count($orderArray)>0)){
                return $this->send($orderArray, "阿姨收入信息查询成功");
            }
        }
    }
    
    public function actionGetTaskList(){
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        // 按阿姨id获取阿姨信息
        $settle_id = intval($param['settle_id']);
        if (!empty($settle_id) ) {
            $taskArray = FinanceSettleApplySearch::getTaskArrayBySettleId($settle_id);
            if(!empty($taskArray)){
                return $this->send($taskArray, "阿姨收入信息查询成功");
            }
        }
    }
    
    public function actionGetDeductionList(){
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        // 按阿姨id获取阿姨信息
        $settle_id = intval($param['settle_id']);
        if (!empty($settle_id) ) {
            $deductionArray = FinanceSettleApplySearch::getDeductionArrayBySettleId($settle_id);
            if(!empty($deductionArray)){
                return $this->send($deductionArray, "阿姨收入信息查询成功");
            }
        }
    }
    
    /**
     * 阿姨确认结算单
     * @return type
     */
    public function actionWorkerConfirmSettlement(){
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        // 按阿姨id获取阿姨信息
        $settle_id = intval($param['settle_id']);
        if (!empty($settle_id) ) {
            $isSucceed = FinanceSettleApplySearch::workerConfirmSettlement($settle_id);
            return $this->send($isSucceed, "阿姨结算状态更新成功");
        }
    }
}
