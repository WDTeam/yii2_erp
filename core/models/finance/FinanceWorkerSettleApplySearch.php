<?php

namespace core\models\finance;

use Yii;
use yii\data\ActiveDataProvider;

use core\models\worker\Worker;
use core\models\order\Order;
use core\models\order\OrderSearch;
use core\models\finance\FinanceWorkerNonOrderIncomeSearch;
use core\models\finance\FinanceWorkerOrderIncomeSearch;

use dbbase\models\order\OrderStatusDict;
use dbbase\models\finance\FinanceWorkerSettleApply;

/**
 * FinanceWorkerSettleApplySearch represents the model behind the search form about `dbbase\models\finance\FinanceWorkerSettleApply`.
 */
class FinanceWorkerSettleApplySearch extends FinanceWorkerSettleApply
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
    
    public $operationDes = [self::SELF_OPERATION=>'自营',self::NON_SELF_OPERATION=>'小家政'];
    
    const FULLTIME = 1;//全职
    
    const PARTTIME = 2;//兼职
    
    const PARKTIME = 3;//高峰
    
    const INTERVALTIME = 4;//时段
    
    public $roleDes = [self::FULLTIME=>'全职',self::PARTTIME=>'兼职',self::PARKTIME=>'高峰',self::INTERVALTIME=>'时段'];
            
    public $latestSettleTime;//上次结算日期
    
    public $settleMonth;//结算月份
    
    const WORKER_CONFIRM_SETTLEMENT = 1;//阿姨确认结算单
    
    const WORKER_VACATION_DAYS = 4;//公司规定阿姨每个月可休假的天数
    
    public $settle_apply_create_start_time;//结算申请开始时间
    
    public $settle_apply_create_end_time;//结算申请结束时间
    
    const SETTLE_CODE_PREFIX = '03';//结算编号的前缀
   
   public $financeSettleApplyStatusArr = [
       FinanceWorkerSettleApply::FINANCE_SETTLE_APPLY_STATUS_FINANCE_FAILED=>'财务审核不通过',
       FinanceWorkerSettleApply::FINANCE_SETTLE_APPLY_STATUS_BUSINESS_FAILED=>'业务部门审核不通过',
       FinanceWorkerSettleApply::FINANCE_SETTLE_APPLY_STATUS_INIT=>'提出申请，正在业务部门审核',
       FinanceWorkerSettleApply::FINANCE_SETTLE_APPLY_STATUS_BUSINESS_PASSED=>'业务部门审核通过，等待财务审核',
       FinanceWorkerSettleApply::FINANCE_SETTLE_APPLY_STATUS_FINANCE_PASSED=>'财务审核通过',
       FinanceWorkerSettleApply::FINANCE_SETTLE_APPLY_STATUS_FINANCE_PAYED=>'财务已确认打款',];
   
    public function rules()
    {
        return [
            [[ 'settle_apply_create_start_time', 'settle_apply_create_end_time'], 'required','on'=>['query']],
             [['worker_id','id', 'shop_id', 'finance_worker_settle_apply_man_hour', 'finance_worker_settle_apply_status','worker_type_id','worker_identity_id', ], 'integer','on'=>['query','count','save','default']],
            [['worker_tel',], 'string', 'max' => 11,'on'=>['query','count','save','default']],
            [['worker_tel',],'match','pattern'=>'/^(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/','message'=>'请填写正确格式的手机号码','on'=>['query','count','save','default']],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return [
            'query'=>[ 'settle_apply_create_start_time', 'settle_apply_create_end_time','worker_tel', 'finance_worker_settle_apply_status','worker_type_id','worker_identity_id'],
            'count'=>[ 'worker_tel'],
            'save'=>[ 'worker_tel'],
            'default'=>['worker_id','id', 'shop_id', 'finance_worker_settle_apply_man_hour', 'finance_worker_settle_apply_status','worker_type_id','worker_identity_id','worker_tel', ],
        ];
    }

    public function search($params)
    {
        $query = FinanceWorkerSettleApplySearch::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->andFilterWhere([
            'id' => $this->id,
            'finance_shop_settle_apply_id' => $this->finance_shop_settle_apply_id,
            'worker_id' => $this->worker_id,
            'worker_type_id' => $this->worker_type_id,
            'worker_identity_id' => $this->worker_identity_id,
            'shop_id' => $this->shop_id,
            'shop_manager_id' => $this->shop_manager_id,
            'finance_worker_settle_apply_man_hour' => $this->finance_worker_settle_apply_man_hour,
            'finance_worker_settle_apply_order_money' => $this->finance_worker_settle_apply_order_money,
            'finance_worker_settle_apply_order_cash_money' => $this->finance_worker_settle_apply_order_cash_money,
            'finance_worker_settle_apply_order_money_except_cash' => $this->finance_worker_settle_apply_order_money_except_cash,
            'finance_worker_settle_apply_money' => $this->finance_worker_settle_apply_money,
            'finance_worker_settle_apply_status' => $this->finance_worker_settle_apply_status,
            'finance_worker_settle_apply_cycle' => $this->finance_worker_settle_apply_cycle,
            'is_softdel' => $this->is_softdel,
            'updated_at' => $this->updated_at,
            'worker_tel' => $this->worker_tel,
        ]);
        $query->andFilterWhere(['between','created_at',$this->settle_apply_create_start_time,$this->settle_apply_create_end_time]);
        $query->andFilterWhere(['like', 'worker_type_name', $this->worker_type_name])
                ->andFilterWhere(['like', 'worker_identity_name', $this->worker_identity_name])
            ->andFilterWhere(['like', 'finance_worker_settle_apply_cycle_des', $this->finance_worker_settle_apply_cycle_des])
            ->andFilterWhere(['like', 'finance_worker_settle_apply_reviewer', $this->finance_worker_settle_apply_reviewer]);
        $dataProvider->query->orderBy(['created_at'=>SORT_DESC]);
        return $dataProvider;
    }
    
    public function searchCanSettledWorker($params)
    {
        $query = FinanceWorkerSettleApplySearch::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->andFilterWhere([
            'shop_id' => $this->shop_id,
            'worker_type_id'=>FinanceWorkerSettleApplySearch::NON_SELF_OPERATION
        ]);
        $query->andFilterWhere(['>=', 'finance_worker_settle_apply_status',self::FINANCE_SETTLE_APPLY_STATUS_FINANCE_PASSED]);
        $dataProvider->query->orderBy(['created_at'=>SORT_DESC]);
        return $dataProvider;
    }
    
    public function getWorkerIncomeAndDetail(){
        $query = new \yii\db\Query();
        $workerIncomeAndDetail = $query->select(['shop.name as shop_name', 'worker.worker_name', 'worker.worker_idcard', 'workerext.worker_bank_card','settleapply.worker_id','settleapply.id as settleApplyId', 'worker.id'])
              ->from('{{%finance_worker_settle_apply}} as settleapply')
              ->innerJoin('{{%worker}} as worker','settleapply.worker_id = worker.id')
              ->innerJoin('{{%shop}} as shop','shop.id = worker.shop_id')
              ->innerJoin('{{%worker_ext}} as workerext','workerext.worker_id=settleapply.worker_id')
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
     * 判断指定类型的阿姨是否存在
     * @param type $workerPhone
     * @param type $worker_type
     * @return boolean
     */
    public function isWorkerExist($workerPhone,$worker_type){
        $isWorkerExist = false;
        $worker = Worker::getWorkerInfoByPhone($workerPhone);
        if(isset($worker['id']) && ($worker['worker_type'] == $worker_type)){
            return true;
        }
        return $isWorkerExist;
    }
    
    /**
     * 根据阿姨手机号获取阿姨信息
     * @param type $workerPhone
     */
    public function getWorkerInfo($workerPhone){
        $workerInfo = Worker::getWorkerInfoByPhone($workerPhone);
        if(count($workerInfo)> 0){
            $this->worker_id =$workerInfo['id'];
            $this->worker_name = $workerInfo['worker_name'];
            $this->worker_tel= $workerInfo['worker_phone'];
            $this->workerOnboardTime= $workerInfo['created_ad'];
            $this->worker_type_id = $workerInfo['worker_type'];
            $this->worker_identity_id = $workerInfo['worker_identity_id'];
            $this->workerTypeDes= $this->getWorkerTypeDes($this->worker_type_id,$this->worker_identity_id);
            $this->finance_worker_settle_apply_cycle_des = $this->getSettleCycleByWorkerType($this->worker_type_id,$this->worker_identity_id);
            $this->latestSettleTime = $this->getWorkerLatestSettledTime($workerInfo['id']);
        }
    }
    
    public function getWorkerTypeDes($worker_type_id,$worker_identity_id){
          return $this->getWorkerTypeName($worker_type_id).$this->getWorkerIdentityDes($worker_identity_id);
    }
    
    public function getSettleApplyStatusDes($settleApplyStatus){
        return $this->financeSettleApplyStatusArr[$settleApplyStatus];
    }
    
    /**
     * 根据阿姨Id获取结算的整体信息
     * @param type $workerId
     */
    public function getWorkerSettlementSummaryInfo($workerId,$finance_worker_settle_apply_starttime,$finance_worker_settle_apply_endtime){
        $orders = $this->getWorkerOrderInfo($workerId);
        $order_count = 0;//总单量
        $apply_man_hour = 0;//总工时
        $apply_order_money = 0;//工时费小计
        $apply_task_count = 0;//完成任务数,从任务系统获取
        $apply_task_money = 0;//完成任务奖励,从任务系统获取
        $apply_base_salary = 0;//底薪
        $apply_base_salary_subsidy = 0;//底薪补贴
        $apply_money_except_deduct_cash = 0;//应结合计,没有减除扣款和现金
        $apply_money_deduction = 0;//扣款小计;包括投诉扣款、赔偿扣款等
        $apply_money_except_cash = 0;//本次应结合计，减掉扣款，没有减除现金
        $order_cash_count = 0;//现金订单
        $order_cash_money = 0;//收取现金
        $apply_money = 0;//本次应付合计
        $order_noncash_count = 0;//非现金订单
        $order_money_except_cash = 0;//工时费应结，扣除了现金
        $this->worker_id = $workerId;
        $workerInfo = Worker::getWorkerInfo($workerId);
        if(count($workerInfo)>0){
            $this->worker_tel = $workerInfo['worker_phone'];
            $this->worker_name = $workerInfo['worker_name'];
            $this->worker_type_id = $workerInfo['worker_type'];
            $this->worker_identity_id = $workerInfo['worker_identity_id'];
            $this->worker_type_name = $this->getWorkerTypeName($workerInfo['worker_type']);
            $this->worker_identity_name = $this->getWorkerIdentityDes($workerInfo['worker_identity_id']);
            $this->shop_id = $workerInfo['shop_id'];
            $this->shop_name = $workerInfo['shop_name'];
            $this->shop_manager_name = $workerInfo['shop_manager_name'];
            $this->shop_manager_id = $workerInfo['shop_manager_id'];
        }
        $this->finance_worker_settle_apply_starttime = $finance_worker_settle_apply_starttime;//结算开始日期
        $this->finance_worker_settle_apply_endtime = $finance_worker_settle_apply_endtime;//结算截止日期
        $apply_base_salary = $this->getBaseSalary($this->worker_type_id,$this->worker_identity_id);
        $apply_task_count = FinanceWorkerNonOrderIncomeSearch::getTaskAwardCount($workerId, $this->finance_worker_settle_apply_starttime, $this->finance_worker_settle_apply_endtime);
        $apply_task_money = FinanceWorkerNonOrderIncomeSearch::getTaskAwardMoney($workerId, $this->finance_worker_settle_apply_starttime, $this->finance_worker_settle_apply_endtime);
        $apply_money_deduction = FinanceWorkerNonOrderIncomeSearch::getCompensateMoney($workerId, $this->finance_worker_settle_apply_starttime, $this->finance_worker_settle_apply_endtime);
        if(count($orders) > 0){
           $order_count = count($orders);
           foreach($orders as $order){
               $apply_man_hour += $order->order_booked_count;
               $apply_order_money += $order->order_money;
              if(!empty($order->orderExtPay) && ($order->orderExtPay->pay_channel_id == 2)){
                  $order_cash_count++;
                  $order_cash_money += $order->order_money;
              }
              if(!empty($order->orderExtPay) && ($order->orderExtPay->pay_channel_id != 2)){
                  $order_noncash_count++;
                  $order_money_except_cash += $order->order_money;
              }
           }
        }
        $apply_base_salary_subsidy  = $this->getBaseSalarySubsidy($workerId,$apply_order_money,$apply_base_salary,$this->worker_type_id,$this->worker_identity_id,$this->finance_worker_settle_apply_starttime,$this->finance_worker_settle_apply_endtime);
        $this->finance_worker_settle_apply_base_salary_subsidy =  $apply_base_salary_subsidy;
        $apply_money_except_deduct_cash = $apply_order_money + $apply_base_salary_subsidy + $apply_task_money;//订单金额+底薪补贴+任务奖励
        $apply_money_except_cash = $apply_money_except_deduct_cash - $apply_money_deduction;//订单金额+底薪补贴+任务奖励-扣款
        $apply_money = $apply_money_except_cash - $order_cash_money;//订单金额+底薪补贴+任务奖励-扣款-现金订单金额
        $this->finance_worker_settle_apply_order_count = $order_count;//总单量
        $this->finance_worker_settle_apply_man_hour = $apply_man_hour;//总工时
        $this->finance_worker_settle_apply_order_money = $apply_order_money;//工时费小计
        $this->finance_worker_settle_apply_task_count = $apply_task_count;//完成任务数
        $this->finance_worker_settle_apply_task_money = $apply_task_money;//完成任务奖励
        $this->finance_worker_settle_apply_base_salary = $apply_base_salary;//底薪
        $this->finance_worker_settle_apply_money_except_deduct_cash = $apply_money_except_deduct_cash;//应结合计,没有减除扣款和现金
        $this->finance_worker_settle_apply_money_deduction = $apply_money_deduction;//扣款小计
        $this->finance_worker_settle_apply_money_except_cash = $apply_money_except_cash;//本次应结合计，没有减除现金
        $this->finance_worker_settle_apply_order_cash_count = $order_cash_count;//现金订单
        $this->finance_worker_settle_apply_order_cash_money = $order_cash_money;//收取现金
        $this->finance_worker_settle_apply_money =$apply_money;//本次应付合计
        $this->finance_worker_settle_apply_order_noncash_count = $order_noncash_count;//非现金订单
        $this->finance_worker_settle_apply_order_money_except_cash = $order_money_except_cash;//工时费应结，扣除了现金
        $this->finance_worker_settle_apply_cycle = $this->getSettleCycleIdByWorkerType($this->worker_type_id, $this->worker_identity_id);//结算周期Id
        $this->finance_worker_settle_apply_cycle_des = $this->getSettleCycleByWorkerType($this->worker_type_id, $this->worker_identity_id);//结算周期描述
        $this->finance_worker_settle_apply_status = FinanceWorkerSettleApply::FINANCE_SETTLE_APPLY_STATUS_INIT;//提交结算申请
        $this->finance_worker_settle_apply_code = $this->getSettleApplyCode();//获取结算编码
        $this->created_at = time();//申请创建时间
        return $this;

    }
    
    /**
     * 获取阿姨底薪
     * @param type $workerType
     * @param type $workerIdentityId
     * @return type
     */
    public function getBaseSalary($workerType,$workerIdentityId){
        $base_salary = 0;
        if($this->isSelfAndFulltimeWorker($workerType, $workerIdentityId)){
            $base_salary = Yii::$app->params['worker_base_salary'];
        }
        return $base_salary;
    }
    
    /**
     * 获取阿姨的底薪补贴
     * @param type $worker_id
     * @param type $apply_order_money
     * @param type $apply_base_salary
     * @param type $workerType
     * @param type $workerIdentityId
     * @param type $finance_worker_settle_apply_starttime
     * @param type $finance_worker_settle_apply_endtime
     * @return type
     */
    public function getBaseSalarySubsidy($worker_id,$apply_order_money,$apply_base_salary,$workerType,$workerIdentityId,$finance_worker_settle_apply_starttime,$finance_worker_settle_apply_endtime){
        $baseSalarySubsidy = 0;
        if($this->isSelfAndFulltimeWorker($workerType, $workerIdentityId)){
             $needWorkDay = date('t',$finance_worker_settle_apply_starttime) - self::WORKER_VACATION_DAYS;//本月应服务天数
             $realWorkDay = date('t',$finance_worker_settle_apply_starttime) - Worker::getWorkerNotWorkTime($worker_id, $finance_worker_settle_apply_starttime, $finance_worker_settle_apply_endtime);//实际工作天数,从阿姨接口获取
             if($realWorkDay >= $needWorkDay){
                 $realWorkDay = $needWorkDay;
             }
             $baseSalarySubsidy = max($apply_order_money,$apply_base_salary/$needWorkDay*$realWorkDay) - $apply_order_money;
        }elseif($this->isNonSelfAndFulltimeWorker($workerType,$workerIdentityId)){
             $orderCount = FinanceWorkerOrderIncomeSearch::getOrderCountByWorkerId($worker_id, $finance_worker_settle_apply_starttime, $finance_worker_settle_apply_endtime);
             if($orderCount < Yii::$app->params['order_count_per_week']){
                 $orderSubsidyCount = Yii::$app->params['order_count_per_week'] - $orderCount;
                 $baseSalarySubsidy = $orderSubsidyCount * Yii::$app->params['unit_order_money_nonself_fulltime'];
             }
        }
        return $baseSalarySubsidy;
    }
    
    
    
    /**
     * 判断是否为自营全时段阿姨
     * @param type $workerType
     * @param type $workerIdentityId
     * @return boolean
     */
    public function isSelfAndFulltimeWorker($workerType,$workerIdentityId){
        $isSelfAndFulltimeWorker = false;
        if(($workerType == self::SELF_OPERATION) && ($workerIdentityId == self::FULLTIME)){
            $isSelfAndFulltimeWorker = true;
        }
        return $isSelfAndFulltimeWorker;
    }
    
    /**
     * 判断阿姨是否为非自营（小家政）全时段阿姨
     * @param type $workerType
     * @param type $workerIdentityId
     */
    public function isNonSelfAndFulltimeWorker($workerType,$workerIdentityId){
        $isNonSelfAndFulltimeWorker = false;
        if(($workerType == self::NON_SELF_OPERATION) && ($workerIdentityId == self::FULLTIME)){
            $isNonSelfAndFulltimeWorker = true;
        }
        return $isNonSelfAndFulltimeWorker;
    }
    
    public function getWorkerOrderInfo($workerId){
        return  Order::find()->joinWith('orderExtWorker')->joinWith('orderExtStatus')->where(['orderExtWorker.worker_id'=>$workerId,'orderExtStatus.order_status_dict_id'=>OrderStatusDict::ORDER_CUSTOMER_ACCEPT_DONE])->all();
    }
    
    public function getWorkerTypeName($workerType){
        return $this->operationDes[$workerType];
    }
    
    public function getWorkerIdentityDes($workerIdentityId){
        return $this->roleDes[$workerIdentityId];
    }
    
    public static function getSettleApplyCode(){
        $dateStr = date("y").date("m").date("d");
        return self::SETTLE_CODE_PREFIX.$dateStr.time();
    }
    
    public function getWorkerIdByWorkerTel($worker_tel){
        $worker_id = -1;
        $worker = Worker::getWorkerInfoByPhone($worker_tel);
        if(count($worker) > 0){
            $worker_id = $worker['id'];
        }
        return $worker_id;
    }
    
    /**
     * 根据用户角色和阿姨类型获取结算周期
     * @param type $workerType
     * @param type $workerRuleId
     */
    public function getSettleCycleByWorkerType($workerType,$workerIdentityId){
        if($this->isSelfAndFulltimeWorker($workerType, $workerIdentityId)){
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
    public function getSettleCycleIdByWorkerType($workerType,$workerIdentityId){
        if(($workerType == self::SELF_OPERATION) && ($workerIdentityId == self::FULLTIME)){
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
                ->where(['worker_id'=>$workerId,'finance_worker_settle_apply_status'=>FinanceWorkerSettleApply::FINANCE_SETTLE_APPLY_STATUS_FINANCE_PAYED])
                ->orderBy("updated_at DESC")
                ->one();
        if($worker != null){
            $latestSettledTime = $worker->updated_at;
        }
        return $latestSettledTime;
    }
    
    /**
     * 获取指定月份的第一天
     * @return type
     */
    public static function getFirstDayOfSpecifiedMonth($yearAndMonth = null){
        if($yearAndMonth == null){
            $yearAndMonth = date('Y-m', strtotime('-1 month'));
        }
        return strtotime(date('Y-m-01 00:00:00', strtotime($yearAndMonth)));
    }
    
    /**
     * 获取指定月份的最后一天
     */
    public static function getLastDayOfSpecifiedMonth($yearAndMonth = null){
        if($yearAndMonth == null){
            $yearAndMonth = date('Y-m', strtotime('-1 month'));
        }
        return strtotime(date('Y-m-t 23:59:59', strtotime($yearAndMonth)));
    }
    
    public static function getFirstDayOfLastWeek(){
        return strtotime(date('Y-m-d 00:00:00', strtotime('-1 week last monday')));
    }
    
    public static function getLastDayOfLastWeek(){
        return strtotime(date('Y-m-d 23:59:59', strtotime('last sunday')));
    }
    
    public static function getWorkerIncomeSummaryInfoByWorkerId($worker_id){
        $workerSummaryInfo = self::find()->select(['sum(finance_worker_settle_apply_order_count) as all_order_count','sum(finance_worker_settle_apply_money) as all_worker_money'])
                ->where(['worker_id'=>$worker_id])->asArray()->one();
        $workerInfo = Worker::getWorkerInfo($worker_id);
        $workerSummaryInfo['worker_name'] = $workerInfo['worker_name'];
        return $workerSummaryInfo;
    }
    
    /**
     * 根据阿姨Id获取已结算阿姨的收入列表
     * @param type $worker_id
     * @param type $current_page
     * @param type $per_page_num
     */
    public static function getSettledWorkerIncomeListByWorkerId($worker_id,$current_page,$per_page_num){
        $finalWorkerIncomeArr = [];
        $offset = ($current_page - 1) * $per_page_num;
        $workerIncomeArr = self::find()
                ->select([
                    'finance_worker_settle_apply_starttime as settle_starttime',
                    'finance_worker_settle_apply_endtime as settle_endtime',
                    'finance_worker_settle_apply_order_count as order_count',
                    'finance_worker_settle_apply_money as worker_income',
                    'finance_worker_settle_apply_cycle as settle_cycle',
                    'finance_worker_settle_apply_cycle_des as settle_cycle_des',
                    'id as settle_id',
                    'finance_worker_settle_apply_status as settle_status',
                    'finance_worker_settle_apply_task_money as settle_task_money',
                    'finance_worker_settle_apply_base_salary_subsidy as base_salary_subsidy',
                    'finance_worker_settle_apply_money_deduction as money_deduction',
                    'finance_worker_settle_apply_order_money_except_cash as order_money_except_cash',
                    'isWorkerConfirmed',
                    ])
                ->where(['worker_id'=>$worker_id])
                ->offset($offset)->limit($per_page_num)
                ->asArray()->all();
        $i = 0;
        foreach($workerIncomeArr as $workerIncome){
            $finalWorkerIncome = [];
            $finalWorkerIncome['settle_id'] = $workerIncome['settle_id'];
            $finalWorkerIncome['settle_year'] = date('Y',$workerIncome['settle_starttime']);
            $finalWorkerIncome['settle_starttime'] = date('Y-m-d',$workerIncome['settle_starttime']);
            $finalWorkerIncome['settle_endtime'] =  date('Y-m-d',$workerIncome['settle_endtime']);
            $finalWorkerIncome['order_count'] = $workerIncome['order_count'];
            $finalWorkerIncome['worker_income'] = $workerIncome['worker_income'];
            $finalWorkerIncome['settle_cycle'] = $workerIncome['settle_cycle'];
            $finalWorkerIncome['settle_cycle_des'] = $workerIncome['settle_cycle_des'];
            $finalWorkerIncome['settle_task_money'] = $workerIncome['settle_task_money'];
            $finalWorkerIncome['base_salary_subsidy'] = $workerIncome['base_salary_subsidy'];
            $finalWorkerIncome['money_deduction'] = $workerIncome['money_deduction'];
            $finalWorkerIncome['order_money_except_cash'] = $workerIncome['order_money_except_cash'];
            $finalWorkerIncome['isWorkerConfirmed'] = $workerIncome['isWorkerConfirmed'];
            if($workerIncome['settle_status'] == self::FINANCE_SETTLE_APPLY_STATUS_FINANCE_PAYED){
                 $finalWorkerIncome['settle_status'] = '1';//已结算
            }else{
                 $finalWorkerIncome['settle_status'] = '0';//未结算
            }
            $finalWorkerIncomeArr[$i] = $finalWorkerIncome;
            $i++;
        }
        return $finalWorkerIncomeArr;
    }
    
    /**
     * 
     * @param type $settle_id
     * @return type
     */
    public static function getOrderArrayBySettleId($settle_id,$current_page,$per_page_num){
        $finalOrderArray = [];
        $offset = ($current_page - 1) * $per_page_num;
        $orderIncomeArray = FinanceWorkerOrderIncomeSearch::find()
                ->select(['order_id','order_money'])
                ->where(['finance_worker_settle_apply_id'=>$settle_id])
                ->offset($offset)->limit($per_page_num)
                ->asArray()->all();
        $i = 0;
        foreach($orderIncomeArray as $orderIncome){
            $finalOrder = [];
            $finalOrder['order_id'] = $orderIncome['order_id'];
            $finalOrder['order_money'] = $orderIncome['order_money'];
            $order = OrderSearch::getOne($orderIncome['order_id']);
            if(count($order) > 0){
               $finalOrder['order_begin_time'] = date('Y-m-d h:m:s',$order->order_booked_begin_time);
               $finalOrder['order_end_time'] = date('Y-m-d h:m:s',$order->order_booked_end_time);
               $finalOrder['order_code'] =$order->order_code;
            }
            $finalOrderArray[$i] = $finalOrder;
            $i++;
        }
        return $finalOrderArray;
    }
    
    public static function getTaskArrayBySettleId($settle_id){
      $taskArray = FinanceWorkerNonOrderIncomeSearch::find()
              ->select(['finance_worker_non_order_income as task_money','finance_worker_non_order_income_des as task_des'])
              ->where(['finance_worker_settle_apply_id'=>$settle_id,'finance_worker_non_order_income_type'=>FinanceWorkerNonOrderIncomeSearch::NON_ORDER_INCOME_TASK])
              ->asArray()->all();
      return $taskArray;
    }
    
    public static function getDeductionArrayBySettleId($settle_id){
      $deductionArray = FinanceWorkerNonOrderIncomeSearch::find()
              ->select(['finance_worker_non_order_income as deduction_money','finance_worker_non_order_income_des as deduction_des','finance_worker_non_order_income_type as deduction_type','FROM_UNIXTIME(finance_worker_non_order_income_complete_time,\'%Y.%m.%d\') as deduction_time'])
              ->where(['finance_worker_settle_apply_id'=>$settle_id,'finance_worker_non_order_income_type'=>[FinanceWorkerNonOrderIncomeSearch::NON_ORDER_INCOME_DEDUCTION_COMPLAINT,FinanceWorkerNonOrderIncomeSearch::NON_ORDER_INCOME_DEDUCTION_COMPANSATE]])
              ->asArray()->all();
      return $deductionArray;
    }
    
    public static function workerConfirmSettlement($settle_id){
        $financeSettleApplySearch = self::find()->where(['id'=>$settle_id])->one();
        $financeSettleApplySearch->isWorkerConfirmed = self::WORKER_CONFIRM_SETTLEMENT;
        return $financeSettleApplySearch->save();
    }
    
    /**
     * 获取可付款的结算列表
     * @return type
     */
    public function getCanPayedSettlementList(){
        $financeSettleApplySearchArray = self::find()->where(['finance_worker_settle_apply_status'=>self::FINANCE_SETTLE_APPLY_STATUS_FINANCE_PASSED])->all();
        return $financeSettleApplySearchArray;
    }
    
    public function attributeLabels()
    {
        $parentAttributeLabels = parent::attributeLabels();
        $addAttributeLabels = [
            'settleMonth' => Yii::t('app', '结算月份'),
            'settle_apply_create_start_time' => Yii::t('app', '申请开始时间'),
            'settle_apply_create_end_time' => Yii::t('app', '申请结束时间'),
        ];
        return array_merge($addAttributeLabels,$parentAttributeLabels);
    }
            
}
