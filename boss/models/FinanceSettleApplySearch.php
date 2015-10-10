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
    
    public $settleMonth;//结算月份
   
   public $financeSettleApplyStatusArr = [-2=>'财务审核不通过',-1=>'业务部门审核不通过'
      ,0=>'提出申请，正在业务部门审核',1=>'业务部门审核通过，等待财务审核',2=>'财务审核通过'];
   
    public function rules()
    {
        return [
            [[ 'finance_settle_apply_starttime', 'finance_settle_apply_endtime'], 'required'],
             [['worder_id', 'worker_type_id', 'finance_settle_apply_man_hour', 'finance_settle_apply_status', ], 'integer'],
            [['worder_tel'], 'string', 'max' => 11],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = FinanceSettleApplySearch::find();
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
            'isdel' => $this->isdel,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'worder_tel' => $this->worder_tel,
        ]);
        $query->andFilterWhere(['<=','finance_settle_apply_starttime',$this->finance_settle_apply_starttime])
              ->andFilterWhere(['<=','finance_settle_apply_endtime',$this->finance_settle_apply_endtime]);
        $query->andFilterWhere(['like', 'worker_type_name', $this->worker_type_name])
            ->andFilterWhere(['like', 'finance_settle_apply_cycle_des', $this->finance_settle_apply_cycle_des])
            ->andFilterWhere(['like', 'finance_settle_apply_reviewer', $this->finance_settle_apply_reviewer]);

        return $dataProvider;
    }
    
    public function getWorkerIncomeAndDetail(){
        $query = new \yii\db\Query();
        $workerIncomeAndDetail = $query->select(['shop.name as shop_name', 'worker.worker_name', 'worker.worker_idcard', 'workerext.worker_bank_card','settleapply.worder_id','settleapply.id as settleApplyId', 'worker.id'])
              ->from('{{%finance_settle_apply}} as settleapply')
              ->innerJoin('{{%worker}} as worker','settleapply.worder_id = worker.id')
              ->innerJoin('{{%shop}} as shop','shop.id = worker.shop_id')
              ->innerJoin('{{%worker_ext}} as workerext','workerext.worker_id=settleapply.worder_id')
              ->all();
        $workerIncomeAndDetail = array([
            'shop_name'=>'望京店',
            'worker_name'=>'陈阿姨',
            'worker_idcard'=>'4210241984',
            'worker_bank_card'=>'620219841139',
            'settleApplyId'=>1,
            'id'=>111,],
        );
        return $workerIncomeAndDetail;
    }
    
    
    public function getWorkerInfo($workerId){
        $financeSettleApplySearch = new FinanceSettleApplySearch;
        $financeSettleApplySearch->worder_id =1234;
        $financeSettleApplySearch->workerName = "张三";
        $financeSettleApplySearch->workerPhone= "13456789000";
        $financeSettleApplySearch->workerOnboardTime= "1443324337";
        $financeSettleApplySearch->workerType= "全职全日";
        $financeSettleApplySearch->finance_settle_apply_cycle_des = $this->getSettleCycleByWorkerType('','');
        $financeSettleApplySearch->latestSettleTime = time();
//        $financeSettleApplySearch->latestSettleTime = $this->getWorkerLatestSettledTime($workerId);
        return $financeSettleApplySearch;
    }
    
    public function getSettleApplyStatusDes($settleApplyStatus){
        return $this->financeSettleApplyStatusArr[$settleApplyStatus];
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
            'settleMonth' => Yii::t('app', '结算月份'),
        ];
        return array_merge($addAttributeLabels,$parentAttributeLabels);
    }
            
}
