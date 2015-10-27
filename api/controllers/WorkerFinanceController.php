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
        if (!isset($param['access_token']) || !$param['access_token'] || !isset($param['worker_id']) || !$param['worker_id'] || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }
        // 按阿姨id获取阿姨信息
        $workerId = intval($param['worker_id']);
        if (!empty($workerId) ) {
            $workerSummaryInfo = FinanceSettleApplySearch::getWorkerIncomeSummaryInfoByWorkerId($workerId);
            if(!empty($workerSummaryInfo)){
                return $this->send($workerSummaryInfo, "阿姨收入信息查询成功");
            }
        }
    }
}
