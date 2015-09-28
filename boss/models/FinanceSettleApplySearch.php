<?php

namespace boss\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FinanceSettleApply;

/**
 * FinanceSettleApplySearch represents the model behind the search form about `common\models\FinanceSettleApply`.
 */
class FinanceSettleApplySearch extends FinanceSettleApply
{
   public $ids;
   
   public $nodeId;
   
   public $subsidyStr;
   
   public $workerName;
    
    public $workerPhone;
    
    public $workerOnboardTime;
    
    public $workerType;
    
    public $latestSettleTime;
   
   public $financeSettleApplyStatusArr = [-4=>'财务确认结算未通过',-3=>'财务审核不通过',-2=>'线下审核不通过',-1=>'门店财务审核不通过'
      ,0=>'提出申请，正在门店财务审核',1=>'门店财务审核通过，等待线下审核',2=>'线下审核通过，等待财务审核',3=>'财务审核通过等待财务确认结算',4=>'财务确认结算'];
   
    public function rules()
    {
        return [
            [['id', 'worder_id', 'worker_type_id', 'finance_settle_apply_man_hour', 'finance_settle_apply_status', 'finance_settle_apply_cycle', 'finance_settle_apply_starttime', 'finance_settle_apply_endtime', 'isdel', 'updated_at', 'created_at'], 'integer'],
            [['worder_tel', 'worker_type_name', 'finance_settle_apply_cycle_des', 'finance_settle_apply_reviewer'], 'safe'],
            [['finance_settle_apply_order_money', 'finance_settle_apply_order_cash_money', 'finance_settle_apply_order_money_except_cash', 'finance_settle_apply_subsidy', 'finance_settle_apply_money'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = FinanceSettleApply::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'worder_id' => $this->worder_id,
            'worker_type_id' => $this->worker_type_id,
            'finance_settle_apply_man_hour' => $this->finance_settle_apply_man_hour,
            'finance_settle_apply_order_money' => $this->finance_settle_apply_order_money,
            'finance_settle_apply_order_cash_money' => $this->finance_settle_apply_order_cash_money,
            'finance_settle_apply_order_money_except_cash' => $this->finance_settle_apply_order_money_except_cash,
            'finance_settle_apply_subsidy' => $this->finance_settle_apply_subsidy,
            'finance_settle_apply_money' => $this->finance_settle_apply_money,
            'finance_settle_apply_status' => $this->finance_settle_apply_status,
            'finance_settle_apply_cycle' => $this->finance_settle_apply_cycle,
            'finance_settle_apply_starttime' => $this->finance_settle_apply_starttime,
            'finance_settle_apply_endtime' => $this->finance_settle_apply_endtime,
            'isdel' => $this->isdel,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'worder_tel', $this->worder_tel])
            ->andFilterWhere(['like', 'worker_type_name', $this->worker_type_name])
            ->andFilterWhere(['like', 'finance_settle_apply_cycle_des', $this->finance_settle_apply_cycle_des])
            ->andFilterWhere(['like', 'finance_settle_apply_reviewer', $this->finance_settle_apply_reviewer]);

        return $dataProvider;
    }
    
    public function getWorkerInfo($workerId){
        $financeSettleApplySearch = new FinanceSettleApplySearch;
        $financeSettleApplySearch->worder_id =1234;
        $financeSettleApplySearch->workerName = "张三";
        $financeSettleApplySearch->workerPhone= "13456789000";
        $financeSettleApplySearch->workerOnboardTime= "1443324337";
        $financeSettleApplySearch->workerType= "全职全日";
        $financeSettleApplySearch->finance_settle_apply_cycle_des = $this->getSettleCycleByWorkerType($workerType,$workerRuleId);
        $financeSettleApplySearch->latestSettleTime = time();
//        $financeSettleApplySearch->latestSettleTime = $this->getWorkerLatestSettledTime($workerId);
        return $financeSettleApplySearch;
    }
    
    /**
     * 根据用户角色和阿姨类型获取结算周期
     * @param type $workerType
     * @param type $workerRuleId
     */
    public function getSettleCycleByWorkerType($workerType,$workerRuleId){
        return "月结";
    }
    
    /**
     * 获取阿姨最近的结算日期
     * @param type $workerId
     */
    public function getWorkerLatestSettledTime($workerId){
        $latestSettledTime = null;
        $worker = $this->find()
                ->select(['updated_at'])
                ->where(['worder_id'=>$workerId,'finance_settle_apply_status'=>FinanceSettleApply::FINANCE_SETTLE_APPLY_STATUS_COMPLETED])
                ->orderBy("updated_at DESC")
                ->one();
        if($worker != null){
            $latestSettledTime = $worker->updated_at;
        }
        return $latestSettledTime;
    }
    
    public function attributeLabels()
    {
        $parentAttributeLabels = parent::attributeLabels();
        $addAttributeLabels = [
            'workerPhone' => Yii::t('app', '阿姨电话'),
        ];
        return array_merge($addAttributeLabels,$parentAttributeLabels);
    }
            
}
