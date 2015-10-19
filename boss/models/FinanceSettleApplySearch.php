<?php

namespace boss\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FinanceSettleApply;
use core\models\worker\Worker;
use core\models\order\Order;

/**
 * FinanceSettleApplySearch represents the model behind the search form about `common\models\FinanceSettleApply`.
 */
class FinanceSettleApplySearch extends FinanceSettleApply
{
   const SELF_FULLTIME_WORKER_SETTELE = 1;//自营全职阿姨结算
   
   const SELF_PARTTIME_WORKER_SETTELE = 2;//自营兼职阿姨结算
   
   const SHOP_WORKER_SETTELE = 3;//门店阿姨结算
   
   const ALL_WORKER_SETTELE = 4;//所有阿姨结算
   
   const SETTLE_CATEGORY_ALL_ORDER_COUNT = 1;//所有订单
   
   const SETTLE_CATEGORY_ALL_CASH_ORDER_COUNT = 2;//现金订单
   
   const SETTLE_CATEGORY_ALL_NONCASH_ORDER_COUNT = 3;//非现金订单
   
   const SETTLE_CATEGORY_TASKS = 4;//任务
   
   const SETTLE_CATEGORY_SMALL_MAINTAIN = 5;//小保养
   
   const SETTLE_CATEGORY_DEDUCTION = 6;//扣款
   
   public $settle_type;//结算类型
   
   public $review_section;//审核部门
   
   public $subsidyStr;//补贴
   
    public $workerOnboardTime;//阿姨报到时间
    
    public $workerTypeDes;//阿姨类型,例如：'自营全职'等
    
    const SELF_OPERATION = 1;//自营
    
    const NON_SELF_OPERATION = 2;//非自营
    
    public $operationDes = [self::SELF_OPERATION=>'自营',self::NON_SELF_OPERATION=>'非自营'];
    
    const FULLTIME = 1;//全职
    
    const PARTTIME = 2;//兼职
    
    public $roleDes = [self::FULLTIME=>'全职',self::PARTTIME=>'兼职'];
            
    public $latestSettleTime;//上次结算日期
    
    public $settleMonth;//结算月份
   
   public $financeSettleApplyStatusArr = [
       FinanceSettleApply::FINANCE_SETTLE_APPLY_STATUS_FINANCE_FAILED=>'财务审核不通过',
       FinanceSettleApply::FINANCE_SETTLE_APPLY_STATUS_BUSINESS_FAILED=>'业务部门审核不通过',
       FinanceSettleApply::FINANCE_SETTLE_APPLY_STATUS_INIT=>'提出申请，正在业务部门审核',
       FinanceSettleApply::FINANCE_SETTLE_APPLY_STATUS_BUSINESS_PASSED=>'业务部门审核通过，等待财务审核',
       FinanceSettleApply::FINANCE_SETTLE_APPLY_STATUS_FINANCE_PASSED=>'财务审核通过'];
   
    public function rules()
    {
        return [
            [[ 'finance_settle_apply_starttime', 'finance_settle_apply_endtime'], 'required'],
             [['worder_id','id', 'worker_type_id','shop_id', 'finance_settle_apply_man_hour', 'finance_settle_apply_status','worker_type_id','worker_rule_id', ], 'integer'],
            [['worder_tel',], 'string', 'max' => 11],
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

        $query->andFilterWhere([
            'id' => $this->id,
            'worder_id' => $this->worder_id,
            'worker_type_id' => $this->worker_type_id,
            'worker_rule_id' => $this->worker_rule_id,
            'shop_id' => $this->shop_id,
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
                ->andFilterWhere(['like', 'worker_rule_name', $this->worker_rule_name])
            ->andFilterWhere(['like', 'finance_settle_apply_cycle_des', $this->finance_settle_apply_cycle_des])
            ->andFilterWhere(['like', 'finance_settle_apply_reviewer', $this->finance_settle_apply_reviewer]);
        $dataProvider->query->orderBy(['created_at'=>SORT_DESC]);
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
    
    
    
    /**
     * 根据阿姨手机号获取阿姨信息
     * @param type $workerPhone
     */
    public function getWorkerInfo($workerPhone){
        $workerSimple = Worker::getWorkerInfoByPhone($workerPhone);
        $workerInfo = [];
        if(isset($workerSimple['id'])){
            $workerInfo = Worker::getWorkerInfo($workerSimple['id']);
        }
        if(count($workerInfo)> 0){
             $this->worder_id =$workerInfo['id'];
            $this->worder_name = $workerInfo['worker_name'];
            $this->worder_tel= $workerInfo['worker_phone'];
            $this->workerOnboardTime= $workerInfo['created_ad'];
            $this->worker_type_id = $workerInfo['worker_type'];
            $this->worker_rule_id = $workerInfo['worker_rule_id'];
            $this->workerTypeDes= $this->getWorkerTypeDes();
            $this->finance_settle_apply_cycle_des = $this->getSettleCycleByWorkerType($this->worker_type_id,$this->worker_rule_id);
            $this->latestSettleTime = time();
        }
//        $financeSettleApplySearch->latestSettleTime = $this->getWorkerLatestSettledTime($workerId);
    }
    
    public function getWorkerTypeDes(){
          return $this->getWorkerTypeName($this->worker_type_id).$this->getWorkerRuleDes($this->worker_rule_id);
    }
    
    public function getSettleApplyStatusDes($settleApplyStatus){
        return $this->financeSettleApplyStatusArr[$settleApplyStatus];
    }
    
    /**
     * 根据阿姨Id获取结算的整体信息
     * @param type $workerId
     */
    public function getWorkerSettlementSummaryInfo($workerId){
        $orders = $this->getWorkerOrderInfo($workerId);
        $order_count = 0;//总单量
        $order_cash_count = 0;//现金订单
        $order_cash_money = 0;//收取现金
        $order_noncash_count = 0;//非现金订单
        $order_money_except_cash = 0;//工时费应结
        $apply_man_hour = 0;//总工时
        $apply_order_money = 0;//订单总金额
        if(count($orders) > 0){
           $order_count = count($orders);
           foreach($orders as $order){
               $apply_man_hour += $order->order_booked_count;
               $apply_order_money += $order->order_money;
              if($order->orderExtPay->order_pay_type == 1){
                  $order_cash_count++;
                  $order_cash_money += $order->order_money;
              }
              if($order->orderExtPay->order_pay_type == 2){
                  $order_noncash_count++;
                  $order_money_except_cash += $order->order_money;
              }
           }
        }
        $this->finance_settle_apply_order_count = $order_count;//总单量
        $this->finance_settle_apply_order_cash_count = $order_cash_count;//现金订单
        $this->finance_settle_apply_order_cash_money = $order_cash_money;//收取现金
        $this->finance_settle_apply_order_noncash_count = $order_noncash_count;//非现金订单
        $this->finance_settle_apply_order_money_except_cash = $order_money_except_cash;//工时费应结
        $this->finance_settle_apply_man_hour = $apply_man_hour;//总工时
        $this->finance_settle_apply_order_money = $apply_order_money;//订单总金额
        $this->finance_settle_apply_subsidy = 0;
        $this->finance_settle_apply_money =$order_money_except_cash+ $this->finance_settle_apply_subsidy;//应结算金额
        $this->worder_id = $workerId;
        $workerInfo = Worker::getWorkerInfo($workerId);
        if(count($workerInfo)>0){
            $this->worder_tel = $workerInfo['worker_phone'];
            $this->worder_name = $workerInfo['worker_name'];
            $this->worker_type_id = $workerInfo['worker_type'];
            $this->worker_rule_id = $workerInfo['worker_rule_id'];
            $this->worker_type_name = $this->getWorkerTypeName($workerInfo['worker_type']);
            $this->worker_rule_name = $this->getWorkerRuleDes($workerInfo['worker_rule_id']);
        }
        $this->finance_settle_apply_cycle = $this->getSettleCycleIdByWorkerType($this->worker_type_id, $this->worker_rule_id);//结算周期Id
        $this->finance_settle_apply_cycle_des = $this->getSettleCycleByWorkerType($this->worker_type_id, $this->worker_rule_id);//结算周期描述
        $this->finance_settle_apply_status = FinanceSettleApply::FINANCE_SETTLE_APPLY_STATUS_INIT;//提交结算申请
        $this->finance_settle_apply_starttime = time();//结算开始日期
        $this->finance_settle_apply_endtime = time();//结算截止日期
        $this->created_at = time();//申请创建时间
        return $this;

    }
    
    public function getWorkerOrderInfo($workerId){
        return  Order::find()->where(['order_booked_worker_id'=>$workerId])->all();
    }
    
    public function getWorkerTypeName($workerType){
        return $this->operationDes[$workerType];
    }
    
    public function getWorkerRuleDes($workerRuleId){
        return $this->roleDes[$workerRuleId];
    }
    
    /**
     * 根据用户角色和阿姨类型获取结算周期
     * @param type $workerType
     * @param type $workerRuleId
     */
    public function getSettleCycleByWorkerType($workerType,$workerRuleId){
        if(($workerType == self::SELF_OPERATION) && ($workerRuleId == self::FULLTIME)){
            return self::FINANCE_SETTLE_APPLY_CYCLE_MONTH_DES;
        }else{
            return self::FINANCE_SETTLE_APPLY_CYCLE_WEEK_DES;
        }
        
    }
    
     /**
     * 根据用户角色和阿姨类型获取结算周期Id
      * 
     * @param type $workerType
     * @param type $workerRuleId
     */
    public function getSettleCycleIdByWorkerType($workerType,$workerRuleId){
        if(($workerType == self::SELF_OPERATION) && ($workerRuleId == self::FULLTIME)){
            return self::FINANCE_SETTLE_APPLY_CYCLE_MONTH;
        }else{
            return self::FINANCE_SETTLE_APPLY_CYCLE_WEEK;
        }
        
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
            'settleMonth' => Yii::t('app', '结算月份'),
        ];
        return array_merge($addAttributeLabels,$parentAttributeLabels);
    }
            
}
