<?php

namespace core\models\finance;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;

use core\models\order\Order;
use core\models\operation\OperationPayChannel;

use dbbase\models\finance\FinanceWorkerOrderIncome;
use dbbase\models\order\OrderStatusDict;
use dbbase\models\order\OrderExtPay;

/**
 * FinanceWorkerOrderIncomeSearch represents the model behind the search form about `dbbase\models\finance\FinanceWorkerOrderIncome`.
 */
class FinanceWorkerOrderIncomeSearch extends FinanceWorkerOrderIncome
{
    public $worker_name;
    
    public $worker_phone;
    
    public $worker_type;
    
    public $order_count;
    
    public $manage_fee;
    
    public $worker_idcard;
    
    const ONLINE_INCOME = 0;//线上支付订单
    
    const CASH_INCOME = 1;//现金订单
    
    //订单收入类型表，包括纯订单收入和订单补贴
    public $orderIncomeType = array(self::ONLINE_INCOME=>'非现金订单',self::CASH_INCOME=>'现金订单',);
    
    public function rules()
    {
        return [
//            [['worker_id', 'order_id', 'order_service_type_id', 'channel_id', 'order_pay_type_id'], 'required'],
//            [['worker_id', 'order_id', 'order_service_type_id', 'channel_id', 'order_pay_type_id', 'order_booked_begin_time', 'order_booked_count', 'isSettled', 'finance_worker_order_income_starttime', 'finance_worker_order_income_endtime', 'finance_worker_settle_apply_id', 'is_softdel', 'updated_at', 'created_at'], 'integer'],
//            [['order_unit_money', 'order_money', 'finance_worker_order_income_discount_amount', 'order_pay_money', 'finance_worker_order_income_money'], 'number'],
//            [['order_service_type_name', 'order_channel_name', 'order_pay_type_des'], 'string', 'max' => 64]
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = FinanceWorkerOrderIncome::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'worker_id' => $this->worker_id,
            'order_id' => $this->order_id,
            'order_service_type_id' => $this->order_service_type_id,
            'channel_id' => $this->channel_id,
            'order_pay_type_id' => $this->order_pay_type_id,
            'order_booked_begin_time' => $this->order_booked_begin_time,
            'order_booked_count' => $this->order_booked_count,
            'order_unit_money' => $this->order_unit_money,
            'order_money' => $this->order_money,
            'finance_worker_order_income_discount_amount' => $this->finance_worker_order_income_discount_amount,
            'order_pay_money' => $this->order_pay_money,
            'finance_worker_order_income_money' => $this->finance_worker_order_income_money,
            'isSettled' => $this->isSettled,
            'finance_worker_order_income_starttime' => $this->finance_worker_order_income_starttime,
            'finance_worker_order_income_endtime' => $this->finance_worker_order_income_endtime,
            'finance_worker_settle_apply_id' => $this->finance_worker_settle_apply_id,
            'is_softdel' => $this->is_softdel,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'order_service_type_name', $this->order_service_type_name])
            ->andFilterWhere(['like', 'order_channel_name', $this->order_channel_name])
            ->andFilterWhere(['like', 'order_pay_type_des', $this->order_pay_type_des]);

        return $dataProvider;
    }
    
    public function getOrderDataProviderFromOrder($worker_id,$finance_worker_settle_apply_starttime,$finance_worker_settle_apply_endtime){
        $data = [];
        $orders = $this->getCanSettledOrders($worker_id);
        $data = $this->getWorkerOrderIncomeArrayFromOrders($orders,$finance_worker_settle_apply_starttime,$finance_worker_settle_apply_endtime);
        $dataProvider = new ArrayDataProvider([ 'allModels' => $data,]);
        return $dataProvider;
    }
    
    private function getCanSettledOrders($worker_id){
        return Order::find()->joinWith('orderExtWorker')->joinWith('orderExtStatus')->where(['orderExtWorker.worker_id'=>$worker_id,'orderExtStatus.order_status_dict_id'=>OrderStatusDict::ORDER_CUSTOMER_ACCEPT_DONE])->all();
    }
    
    /**
     * 根据结算Id获取所有订单收入的流水信息
     * @param type $settle_id
     * @return ArrayDataProvider
     */
    public function getOrderDataProviderBySettleId($settle_id){
        $data = [];
        $data = self::find()->where(['finance_worker_settle_apply_id'=>$settle_id])->asArray()->all();
        $dataProvider = new ArrayDataProvider([ 'allModels' => $data,]);
        return $dataProvider;
    }
    
    /**
     * 根据结算Id获取现金订单收入的流水信息
     * @param type $settle_id
     * @return ArrayDataProvider
     */
    public function getCashOrderDataProviderBySettleId($settle_id){
        $data = [];
        $data = self::find()->where(['finance_worker_settle_apply_id'=>$settle_id,'order_pay_type_id'=>OperationPayChannel::PAY_CHANNEL_EJJ_CASH_PAY])->asArray()->all();
        $dataProvider = new ArrayDataProvider([ 'allModels' => $data,]);
        return $dataProvider;
    }
    
    
    public function getCashOrderDataProviderFromOrder($worker_id,$finance_worker_settle_apply_starttime,$finance_worker_settle_apply_endtime){
        $data = [];
        $orders = Order::find()->joinWith('orderExtWorker')->joinWith('orderExtPay')->joinWith('orderExtPay')->where(['orderExtWorker.worker_id'=>$worker_id,'orderExtPay.pay_channel_id'=>OperationPayChannel::PAY_CHANNEL_EJJ_CASH_PAY])->all();
        $data = $this->getWorkerOrderIncomeArrayFromOrders($orders,$finance_worker_settle_apply_starttime,$finance_worker_settle_apply_endtime);
        $dataProvider = new ArrayDataProvider([ 'allModels' => $data,]);
        return $dataProvider;
    }
    
    public function getWorkerOrderIncomeArrayByWorkerId($worker_id,$settleStartTime,$settleEndTime){
        $orders = $this->getCanSettledOrders($worker_id);
        return $this->getWorkerOrderIncomeArrayFromOrders($orders,$settleStartTime,$settleEndTime);
    }
    
    public static function getOrderCountByWorkerId($worker_id,$start_time,$end_time){
        return Order::find()->joinWith('orderExtWorker')->joinWith('orderExtStatus')
                ->where(['orderExtWorker.worker_id'=>$worker_id,
                    'orderExtStatus.order_status_dict_id'=>OrderStatusDict::ORDER_CUSTOMER_ACCEPT_DONE
                        ])
//                ->andFilterWhere(['between','order_booked_begin_time',$start_time,$end_time])
                ->count();
    }
    
    /**
     * 根据订单列表信息获取订单收入数组
     * @param type $orders
     * @return type
     */
    public function getWorkerOrderIncomeArrayFromOrders($orders,$settleStartTime,$settleEndTime){
        $data = [];
        $i = 0;
        foreach($orders as $order){
            $data[$i] = $this->transferOrderToFinanceWorkerOrderIncome($order,$settleStartTime,$settleEndTime);
            $i++;
        }
        return $data;
    }
    
    private function transferOrderToFinanceWorkerOrderIncome($order,$settleStartTime,$settleEndTime){
        $financeWorkerOrderIncomeSearch = new FinanceWorkerOrderIncomeSearch();
        $financeWorkerOrderIncomeSearch->worker_id = $order->orderExtWorker->worker_id;
        $financeWorkerOrderIncomeSearch->order_id = $order->id;
        $financeWorkerOrderIncomeSearch->order_code = $order->order_code;
        $financeWorkerOrderIncomeSearch->order_service_type_id = $order->order_service_type_id;
        $financeWorkerOrderIncomeSearch->order_service_type_name = $order->order_service_type_name;
        $financeWorkerOrderIncomeSearch->channel_id = $order->channel_id;
        $financeWorkerOrderIncomeSearch->order_channel_name = $order->order_channel_name;
        $financeWorkerOrderIncomeSearch->order_pay_type_id = $order->orderExtPay->pay_channel_id;
        $orderExtPay = new OrderExtPay;
        $financeWorkerOrderIncomeSearch->order_pay_type_des = OperationPayChannel::get_post_name($order->orderExtPay->pay_channel_id);
        $financeWorkerOrderIncomeSearch->order_booked_begin_time = $order->order_booked_begin_time;
        $financeWorkerOrderIncomeSearch->order_booked_count = $order->order_booked_count;
        $financeWorkerOrderIncomeSearch->order_unit_money = $order->order_unit_money;
        $financeWorkerOrderIncomeSearch->order_money = $order->order_money;
        $financeWorkerOrderIncomeSearch->finance_worker_order_income_discount_amount = $order->order_use_coupon_money;
        $financeWorkerOrderIncomeSearch->order_pay_money = $order->order_money;
        $financeWorkerOrderIncomeSearch->finance_worker_order_income_money = $order->order_money;
        $financeWorkerOrderIncomeSearch->finance_worker_order_income_starttime = $settleStartTime;//结算开始日期
        $financeWorkerOrderIncomeSearch->finance_worker_order_income_starttime = $settleEndTime;//结算截止日期
        $financeWorkerOrderIncomeSearch->created_at = time();//申请创建时间
        return $financeWorkerOrderIncomeSearch;
    }
    
    public function attributeLabels()
    {
        $parentAttributeLabels = parent::attributeLabels();
        $addAttributeLabels = [
            'worker_idcard' => Yii::t('app', '阿姨身份证号'),
            'worker_name' => Yii::t('app', '阿姨姓名'),
            'worker_phone' => Yii::t('app', '阿姨电话'),
            'worker_type' => Yii::t('app', '阿姨类型'),
            'order_count' => Yii::t('app', '完成单量'),
            'manage_fee' => Yii::t('app', '服务费'),
        ];
        return array_merge($addAttributeLabels,$parentAttributeLabels);
    }
    
    
    public function getOrderIncomeTypeDes($status){
        return $this->orderIncomeType[$status];
    }
}
