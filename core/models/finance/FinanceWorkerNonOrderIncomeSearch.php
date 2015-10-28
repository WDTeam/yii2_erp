<?php

namespace core\models\finance;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\finance\FinanceWorkerNonOrderIncome;
use core\models\worker\WorkerTask;
use yii\data\ArrayDataProvider;
use core\models\worker\Worker;
use core\models\finance\FinanceSettleApplySearch;

/**
 * FinanceWorkerNonOrderIncomeSearch represents the model behind the search form about `common\models\finance\FinanceWorkerNonOrderIncome`.
 */
class FinanceWorkerNonOrderIncomeSearch extends FinanceWorkerNonOrderIncome
{
    const NON_ORDER_INCOME_TASK = 1;//阿姨任务收入
    
    const NON_ORDER_INCOME_DEDUCTION_COMPLAINT = 2;//阿姨投诉扣款
    
    const NON_ORDER_INCOME_DEDUCTION_COMPANSATE = 3;//阿姨赔偿扣款
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'worker_id', 'finance_worker_non_order_income_type', 'finance_worker_non_order_income_starttime', 'finance_worker_non_order_income_endtime', 'finance_worker_non_order_income_isSettled', 'finance_settle_apply_id', 'isdel', 'updated_at', 'created_at'], 'integer'],
            [['finance_worker_non_order_income'], 'number'],
            [['finance_worker_non_order_income_des'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = FinanceWorkerNonOrderIncome::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'worker_id' => $this->worker_id,
            'finance_worker_non_order_income_type' => $this->finance_worker_non_order_income_type,
            'finance_worker_non_order_income' => $this->finance_worker_non_order_income,
            'finance_worker_non_order_income_starttime' => $this->finance_worker_non_order_income_starttime,
            'finance_worker_non_order_income_endtime' => $this->finance_worker_non_order_income_endtime,
            'finance_worker_non_order_income_isSettled' => $this->finance_worker_non_order_income_isSettled,
            'finance_settle_apply_id' => $this->finance_settle_apply_id,
            'isdel' => $this->isdel,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'finance_worker_non_order_income_des', $this->finance_worker_non_order_income_des]);

        return $dataProvider;
    }
    
    public static function getSubsidyDetail($settleApplyId){
        $detail = "";
        $nonIncomeArr = FinanceWorkerNonOrderIncome::find()->where(['finance_settle_apply_id'=>$settleApplyId])->all();
        foreach($nonIncomeArr as $nonIncome){
            $detail.=$nonIncome->finance_worker_non_order_income_name.':'.$nonIncome->finance_worker_non_order_income.'|';
        }
        return $detail;
    }
    
    /**
     * 获取任务奖励金额
     * @param type $workerId
     * @param type $finance_settle_apply_starttime
     * @param type $finance_settle_apply_endtime
     * @return type
     */
    public static function getTaskAwardMoney($workerId,$finance_settle_apply_starttime,$finance_settle_apply_endtime){
        $taskAwardMoney = 0;
        $taskAwardList = self::getTaskAwardList($workerId, $finance_settle_apply_starttime, $finance_settle_apply_endtime);
        foreach($taskAwardList as $taskAward){
            $taskAwardMoney += $taskAward ->worker_task_reward_value;
        }
        return $taskAwardMoney;
    }
    
    /**
     * 获取任务数
     * @param type $workerId
     * @param type $finance_settle_apply_starttime
     * @param type $finance_settle_apply_endtime
     * @return type
     */
    public static function getTaskAwardCount($workerId,$finance_settle_apply_starttime,$finance_settle_apply_endtime){
        $taskAwardList = self::getTaskAwardList($workerId, $finance_settle_apply_starttime, $finance_settle_apply_endtime);
        return count($taskAwardList);
    }
    
    /**
     * 获取任务奖励列表
     * @param type $workerId
     * @param type $finance_settle_apply_starttime
     * @param type $finance_settle_apply_endtime
     * @return type
     */
    public static function getTaskAwardList($workerId,$finance_settle_apply_starttime,$finance_settle_apply_endtime){
        return WorkerTask::getDoneTasksByWorkerId($finance_settle_apply_starttime, $finance_settle_apply_endtime, $workerId);
    }
    
    public function getTaskDataProviderByWorkerId($workerId,$finance_settle_apply_starttime,$finance_settle_apply_endtime){
        $data = $this->getTaskArrByWorkerId($workerId,$finance_settle_apply_starttime,$finance_settle_apply_endtime);
        $dataProvider = new ArrayDataProvider([ 'allModels' => $data,]);
        return $dataProvider;
    }
    
    public function getTaskArrByWorkerId($workerId,$finance_settle_apply_starttime,$finance_settle_apply_endtime){
        $data = [];
        $taskAwardList = self::getTaskAwardList($workerId, $finance_settle_apply_starttime, $finance_settle_apply_endtime);
        $i = 0;
        foreach($taskAwardList as $taskAward){
            $data[$i] = $this->transferWorkerTasksToWorkerNonOrderIncome($taskAward);
            $i++;
        }
        return $data;
    }
    
    private function transferWorkerTasksToWorkerNonOrderIncome($taskAward){
        $financeWorkerNonOrderIncome = new FinanceWorkerNonOrderIncome();
        $financeWorkerNonOrderIncome->worker_id = $taskAward->worker_id;
        $financeWorkerNonOrderIncome->finance_worker_non_order_income_code = $taskAward->worker_task_id;
        $financeWorkerNonOrderIncome->finance_worker_non_order_income_type = self::NON_ORDER_INCOME_TASK;
        $financeWorkerNonOrderIncome->finance_worker_non_order_income_name = $taskAward->worker_task_name;
        $financeWorkerNonOrderIncome->finance_worker_non_order_income = $taskAward->worker_task_reward_value;
        $financeWorkerNonOrderIncome->finance_worker_non_order_income_des = $taskAward->worker_task_description;
        $financeWorkerNonOrderIncome->finance_worker_non_order_income_complete_time = $taskAward->worker_task_done_time;
        $worker = Worker::getWorkerInfo($financeWorkerNonOrderIncome->worker_id);
        if(($worker['worker_type'] ==FinanceSettleApplySearch::SELF_OPERATION ) && ($worker['worker_identity_id'] == FinanceSettleApplySearch::FULLTIME)){
            $financeWorkerNonOrderIncome->finance_worker_non_order_income_starttime = FinanceSettleApplySearch::getFirstDayOfSpecifiedMonth();//结算开始日期
            $financeWorkerNonOrderIncome->finance_worker_non_order_income_endtime = FinanceSettleApplySearch::getLastDayOfSpecifiedMonth();//结算截止日期
        }else{
            $financeWorkerNonOrderIncome->finance_worker_non_order_income_starttime = FinanceSettleApplySearch::getFirstDayOfLastWeek();//结算开始日期
            $financeWorkerNonOrderIncome->finance_worker_non_order_income_endtime = FinanceSettleApplySearch::getLastDayOfLastWeek();//结算截止日期
        }
        $financeWorkerNonOrderIncome->created_at = time();
        return $financeWorkerNonOrderIncome;
    }
    
    public function getNonOrderIncomeBySettleApplyId($settleApplyId){
        $nonOrderIncomeArr =  FinanceWorkerNonOrderIncome::find()->select(['finance_worker_non_order_income_type','finance_worker_non_order_income'])
                 ->where(['finance_settle_apply_id'=>$settleApplyId])->all();
        return $nonOrderIncomeArr;
    }
    
    /**
     * 根据任务Id判断该任务是否已经被结算
     * @param type $task_id
     */
    public static function isWorkerTaskSettled($task_id){
        $isWorkerTaskSettled = false;
        $count = self::find()->join('INNER JOIN', '{{%finance_settle_apply}}', 'finance_settle_apply_id={{%finance_settle_apply}}.id')
                ->where(['finance_worker_non_order_income_code'=>$task_id,'{{%finance_settle_apply}}.finance_settle_apply_status'=>FinanceSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_FINANCE_PAYED])
                ->count();
        if($count > 0){
            $isWorkerTaskSettled = true;
        }
        return isWorkerTaskSettled;
    }
}
