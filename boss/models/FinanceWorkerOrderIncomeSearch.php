<?php

namespace boss\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FinanceWorkerOrderIncome;
use common\models\Order;
use common\models\CustomerAddress;
use common\models\Worker;

/**
 * FinanceWorkerOrderIncomeSearch represents the model behind the search form about `common\models\FinanceWorkerOrderIncome`.
 */
class FinanceWorkerOrderIncomeSearch extends FinanceWorkerOrderIncome
{
    //订单收入类型表，包括纯订单收入和订单补贴
    public $orderIncomeType = array('order_money'=>'0','cash_money'=>'1',
            'far_subsidy'=>'2','night_subsidy'=>'3',
            'empty_handed_subsidy'=>'4','channel_bonus'=>'5',
            'small_maintain'=>'6',
        );
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'worder_id', 'order_id', 'finance_worker_order_income_type', 'order_booked_count', 'isSettled', 'finance_settle_apply_id', 'isdel', 'updated_at', 'created_at'], 'integer'],
            [['finance_worker_order_income'], 'number'],
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
        $query = FinanceWorkerOrderIncome::find();

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
            'worder_id' => $this->worder_id,
            'order_id' => $this->order_id,
            'finance_worker_order_income_type' => $this->finance_worker_order_income_type,
            'finance_worker_order_income' => $this->finance_worker_order_income,
            'order_booked_count' => $this->order_booked_count,
            'isSettled' => $this->isSettled,
            'finance_settle_apply_id' => $this->finance_settle_apply_id,
            'isdel' => $this->isdel,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        return $dataProvider;
    }
    
    /**
     * 计算已完成订单的收入（包括订单收入和补贴收入），并保存到数据库表
     * @param type $orderId
     */
    public function countOrderIncomeAndSave($orderId){
        if(isOrderCompelted($orderId)){
            $order = Order::find()->where(['id' => $orderId,'order_status_dict_id' => 12])->one();
            if(isFulltimeWorker($order)){
                $this->saveFulltimeWorkerIncome($order);
            }else{//兼职阿姨只有订单收入，没有任何补贴
                $financeWorkerOrderIncome = $this->generateModel($order,$order->order_pay_type,$order->order_money);
                $financeWorkerOrderIncome->save();
            }
        }
    }
   
    /***
     * 保存全职阿姨的收入 
     */
    public function saveFulltimeWorkerIncome($order){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            //纯订单收入,包括线上和现金
            $financeWorkerOrderIncome = $this->generateModel($order,$order->order_pay_type,$order->order_money);
            $financeWorkerOrderIncome->save();
            //路补收入
            $financeWorkerFarSubsidy = $this->generateModel($order,$orderIncomeType['far_subsidy'],$this->getFarSubSidy($order));
            $financeWorkerFarSubsidy->save();
            //晚补收入
            $financeWorkerNightSubsidy = $this->generateModel($order,$orderIncomeType['night_subsidy'],$this->getFarSubSidy($order));
            $financeWorkerNightSubsidy->save();
            //扑空补助
            
            //渠道奖励
            //小保养
            $transaction ->commit();
        }catch(Exception $e){
            $transaction ->rollBack();
        }
    }
    
    /**
     * 获取订单收入，不包括补贴，用于保存到订单收入表
     * @param type $order
     */
    public function generateModel($order,$income_type,$income_money){
        $financeWorkerOrderIncome = new FinanceWorkerOrderIncome;
        $financeWorkerOrderIncome->worder_id = $order->worker_id;
        $financeWorkerOrderIncome->order_id = $order->id;
        $financeWorkerOrderIncome->finance_worker_order_income_type = $income_type;
        $financeWorkerOrderIncome->finance_worker_order_income = $income_money;
        $financeWorkerOrderIncome->order_booked_count = $order->order_booked_count;
        $financeWorkerOrderIncome->created_at = time();
        return $financeWorkerOrderIncome;
    }
    
    /**
     * 获取路补收入
     * 逻辑如下：
     * 1.如果当前订单与当天上一笔已完成订单的经纬度直线距离超过7千米，则获取相应的补助
     * 2.如果当前订单是当天第一笔订单，则与阿姨家庭住址的经纬度计算
     * @param type $order
     */
    public function getFarSubSidy($order){
        $subsidy = 0;
        $defaultSubsidy = 10;//补助10元
        $baseKM = 7;//超过该值，则给予补助
        $currentOrderBeginTime  = $order->order_booked_begin_time;//当前订单的预约开始时间
        $currentZeroPoint = strtotime(date('Y-m-d',$currentOrderBeginTime));//当天时间的0点
        $workerId = $order->worker_id;//阿姨Id
        $orderCompletedStatus = 12;//订单已完成
        $customerId = $order->customer_id;//客户Id
        //当前客户的地址详细信息
        $curCustemerAddress = CustomerAddress::find()->where(['customer_id'=>$customerId])->one();
        $curLat = $curCustemerAddress->customer_address_latitude;//当前客户的纬度
        $curLng = $curCustemerAddress->customer_address_longitude;//当前客户的经度
        $lastOrder = Order::find()
                ->where('worker_id = :worker_id and order_status_dict_id = :order_status_dict_id and order_booked_begin_time > :order_booked_begin_time1 and order_booked_begin_time < :order_booked_begin_time2',
                        [':worker_id'=>$workerId,':order_status_dict_id'=>$orderCompletedStatus,':order_booked_begin_time1'=>$currentZeroPoint,':order_booked_begin_time2' =>$currentOrderBeginTime]
                        )
                ->orderBy('order_booked_begin_time desc')
                ->one();
        $distance = 0;
        if(!empty($lastOrder)){//如果有上一笔已完成，则获取上一笔的经纬度
            $preCustomId = $lastOrder->customer_id;//上一笔订单对应客户的Id
            $preCustomerAddress = CustomerAddress::find()->where(['customer_id'=>$preCustomId])->one();
            $preLat = $preCustomerAddress->customer_address_latitude;//上笔订单客户的纬度
            $preLng = $preCustomerAddress->customer_address_longitude;//上笔订单当前客户的经度
            $distance = $this->getStraightDistance($preLat, $preLng, $curLat, $curLng);
        }else{//如果没有上一笔已完成，则获取阿姨住址的经纬度
           $worker =  Worker::find()->where(['id'=>$workerId])->one();
           $workerLat = $worker->worker_work_lat;//阿姨常用工作纬度
           $workerLng =  $worker->worker_work_lng; //阿姨常用工作经度      
           $distance = $this->getStraightDistance($workerLat, $workerLng, $curLat, $curLng);
        }
        if($distance > $baseKM){
                $subsidy = $defaultSubsidy;
            }
        return $subsidy;
    }
    
    /**
     * 获取晚补，逻辑如下：
     * 预约时间在18:00以后的订单，每半小时补助2.5元；
     * 追单不算晚补；
     * @param type $order
     */
    public function getNightSubSidy($order){
        $subsidy = 0;
        $defaultSubsidy = 2.5;
        $serviceDuration = $order->order_booked_count;//服务时长
        $order_parent_id = $order->order_parent_id;
        $orderBookedBeginTime = $order->order_booked_begin_time;
        $baseTime = strtotime(date('Y-m-d',$orderBookedBeginTime).'18:00:00');
        if($orderBookedBeginTime > $baseTime){
            
        }
        Order::find()->where('',[]);
    }
    
    /**
     * 是否为全职阿姨
     * @param type $order
     */
    public function isFulltimeWorker($order){
        $isFulltime = false;
        $workerTypeId = $order->worker_type_id;
        if($workerTypeId == 2){
            $isFulltime = true;
        }
    }
    /**
     * 判断该订单是否已完成
     * @param type $orderId
     * @return boolean
     */
    public function isOrderCompelted($orderId){
        $isOkToCount = false;
        //获取订单信息并判断状态是否为已完成
        $orderCount = Order::find()->where(['id' => $orderId,'order_status_dict_id' => 12])->count();
        if($orderCount == 1){//如果订单已完成
            $isOkToCount = true;
        }
        return $isOkToCount;
    }
    
    /**
     * 根据经纬度获取直线距离
     * @param type $lat1 
     * @param type $lng1
     * @param type $lat2
     * @param type $lng2
     * @return int 千米
     */
    public function getStraightDistance($lat1, $lng1, $lat2, $lng2){
        if ($lat1 * $lng1 * $lat2 * $lng2 == 0) return 0;
        $earthRadius = 6378.137; //地球半径 千米
        //角度转弧度
        $lat1 = ($lat1 * pi()) / 180;
        $lng1 = ($lng1 * pi()) / 180;
        $lat2 = ($lat2 * pi()) / 180;
        $lng2 = ($lng2 * pi()) / 180;
         $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepTwo = 2 * asin(min(1, sqrt(pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2))));
        $calculatedDistance = $earthRadius * $stepTwo;
        $calculatedDistance = round($calculatedDistance * 10) / 10;
        return abs($calculatedDistance);
    }
}
