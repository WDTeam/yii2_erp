<?php

/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/9/23
 * Time: 18:30
 */

namespace core\models\order;

/** core */
use core\models\finance\FinanceRefundadd;

use core\models\operation\coupon\CouponRule;
use core\models\operation\OperationShopDistrictGoods;
use core\models\operation\OperationShopDistrictCoordinate;
use core\models\operation\OperationShopDistrict;
use core\models\operation\OperationGoods;
use core\models\operation\OperationCategory;

use core\models\customer\Customer;
use core\models\customer\CustomerAddress;

use core\models\payment\PaymentCustomerTransRecord;

use core\models\worker\Worker;
use core\models\worker\WorkerStat;

/** dbbase */
use dbbase\models\order\OrderExtPay;
use dbbase\models\order\OrderExtWorker;
use dbbase\models\order\Order as OrderModel;
use dbbase\models\order\OrderExtCustomer;
use dbbase\models\order\OrderSrc;

use dbbase\models\finance\FinanceOrderChannel;

/** yii */
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $order_code
 * @property string $order_parent_id
 * @property integer $order_is_parent
 * @property integer $order_before_status_dict_id
 * @property string $order_before_status_name
 * @property integer $order_status_dict_id
 * @property string $order_status_name
 * @property integer $order_flag_send
 * @property integer $order_flag_urgent
 * @property integer $order_flag_exception
 * @property integer $order_flag_sys_assign
 * @property integer $order_flag_lock
 * @property integer $order_flag_is_checked
 * @property string $order_ip
 * @property integer $order_service_type_id
 * @property string $order_service_type_name
 * @property integer $order_src_id
 * @property string $order_src_name
 * @property integer $channel_id
 * @property string $order_channel_name
 * @property string $order_unit_money
 * @property string $order_money
 * @property string $order_booked_count
 * @property string $order_booked_begin_time
 * @property string $order_booked_end_time
 * @property string $address_id
 * @property string $order_address
 * @property string $order_lat
 * @property string $order_lng
 * @property string $order_booked_worker_id
 * @property string $order_pop_order_code
 * @property string $order_pop_group_buy_code
 * @property string $order_pop_operation_money
 * @property string $order_pop_order_money
 * @property string $order_pop_pay_money
 * @property string $customer_id
 * @property string $order_customer_phone
 * @property string $order_customer_need
 * @property string $order_customer_memo
 * @property string $comment_id
 * @property string $invoice_id
 * @property integer $order_customer_hidden
 * @property integer $order_pay_type
 * @property string $pay_channel_id
 * @property string $order_pay_channel_name
 * @property string $order_pay_flow_num
 * @property string $order_pay_money
 * @property string $order_use_acc_balance
 * @property string $order_is_use_balance
 * @property string $card_id
 * @property string $order_use_card_money
 * @property string $coupon_id
 * @property string $order_coupon_code
 * @property string $order_use_coupon_money
 * @property string $promotion_id
 * @property string $order_use_promotion_money
 * @property string $worker_id
 * @property string $order_worker_phone
 * @property string $order_worker_name
 * @property string $worker_type_id
 * @property string $order_worker_type_name
 * @property integer $order_worker_assign_type
 * @property string $shop_id
 * @property string $order_worker_shop_name
 * @property string $checking_id
 * @property string $order_cs_memo
 * @property string $order_sys_memo
 * @property string $admin_id
 */
class Order extends OrderModel
{

    const ADMIN_SYSTEM = 1;
    const ADMIN_CUSTOMER = 2;
    const ADMIN_WORKER = 3;
    /**
     * 创建新订单
     * @param $attributes [
     *  string $order_ip 下单IP地址 必填
     *  integer $order_service_item_id 服务项id 必填
     *  integer $order_src_id 订单来源id 必填
     *  string $channel_id 下单渠道 必填
     *  int $order_booked_begin_time 预约开始时间 必填
     *  int $order_booked_count 预约时长 必填
     *  int $order_booked_end_time 预约结束时间 必填
     *  int $address_id 客户地址id 必填
     *  int $customer_id 客户id 必填
     *  int $admin_id 操作人id 1系统 2客户 3阿姨 必填
     *  int $order_pay_type 支付方式 1现金 2线上 3第三方 必填
     *  int $coupon_id 优惠券id
     *  int $order_is_use_balance 是否使用余额 0否 1是
     *  string $order_booked_worker_id 指定阿姨id
     *  string $order_pop_order_code 第三方订单号
     *  string $order_pop_group_buy_code 第三方团购号
     *  int $order_pop_order_money 第三方预付金额
     *  string $order_customer_need 客户需求
     *  string $order_customer_memo 客户备注
     *  string $order_flag_sys_assign 是否系统指派
     *  string $order_cs_memo 客服备注
     * ]
     * @return bool
     */
    public function createNew($attributes)
    {
        $attributes_keys = [
            'order_ip','order_service_item_id','order_src_id','channel_id',
            'order_booked_begin_time','order_booked_end_time','address_id',
            'customer_id','admin_id','order_pay_type','order_booked_count',
            'coupon_id','order_is_use_balance','order_booked_worker_id','order_pop_order_code',
            'order_pop_group_buy_code','order_pop_order_money','order_customer_need','order_customer_memo','order_cs_memo','order_flag_sys_assign'
        ];
        $attributes_required = [
            'order_ip','order_service_item_id','order_src_id','channel_id',
            'order_booked_begin_time','order_booked_end_time','address_id',
            'customer_id','admin_id','order_pay_type','order_booked_count'
        ];
        $attributes['order_flag_sys_assign'] = !isset($attributes['order_flag_sys_assign'])?1:$attributes['order_flag_sys_assign'];
        foreach($attributes as $k=>$v){
            if(!in_array($k,$attributes_keys)){
                unset($attributes[$k]);
            }
        }
        foreach($attributes_required as $v){
            if(empty($attributes[$v])){
                $this->addError($v,Order::getAttributeLabel($v).'为必填项！');
                return false;
            }
        }
        if(!in_array($attributes['order_pay_type'],[OrderExtPay::ORDER_PAY_TYPE_ON_LINE,OrderExtPay::ORDER_PAY_TYPE_OFF_LINE,OrderExtPay::ORDER_PAY_TYPE_POP])){
            $this->addError('order_pay_type','支付方式错误！');
            return false;
        }
        $attributes['order_parent_id'] = 0;
        $attributes['order_is_parent'] = 0;

        $customer = Customer::getCustomerById($attributes['customer_id']);
        if(!empty($customer)) {
            $attributes['order_customer_phone'] = $customer->customer_phone;
            $attributes['customer_is_vip'] = $customer->customer_is_vip;
            if(OrderSearch::customerWaitPayOrderCount($customer->customer_phone)>0){
                $this->addError('customer_id', '存在待支付订单，请先支付再下单！');
                return false;
            }
        }else{
            $this->addError('customer_id', '没有获取到用户信息！');
            return false;
        }
        $customer_balance = 0;
        //如果客户选择使用余额则去获取客户余额
        if(!empty($attributes['order_is_use_balance'])) {
            try {
                $customer = Customer::getCustomerInfo($attributes['order_customer_phone']);
                $customer_balance = $customer['customer_balance'];
            } catch (Exception $e) {
                $this->addError('order_use_acc_balance', '创建时获客户余额信息失败！');
                return false;
            }
        }

        if ($this->_create($attributes,null,$customer_balance)) {
            //交易记录1,现金支付,3第三支付,无需线上支付
            if( $this->order_pay_money == 0 ){
                if (PaymentCustomerTransRecord::analysisRecord($this->id, $this->channel_id, 'order_pay')) {
                    $order_model = OrderSearch::getOne($this->id);
                    $order_model->admin_id = $attributes['admin_id'];
                    if(in_array($this->order_service_item_name,Yii::$app->params['order']['USE_ORDER_FLOW_SERVICE_ITEMS'])) {//TODO 判断是否使用订单流程
                        OrderStatus::_payment($order_model, ['OrderExtPay']);
                    }
                }
            }
            return true;
        }
        return false;
    }

    /*
      Array
      (
      [order_ip] => 127.0.0.1
      [order_service_type_id] => 11
      [order_src_id] => 1
      [channel_id] => 1
      [address_id] => 1
      [customer_id] => 3
      [order_customer_phone] => 13683118946
      [admin_id] => 0
      [order_pay_type] => 1
      [order_is_use_balance] => 1
      [order_booked_worker_id] => 1
      [order_customer_need] => 1
      [order_customer_memo] => 1
      )
      Array
      (
      [order_booked_begin_time] => 1420855800
      [order_booked_end_time] => 1420942200
      [coupon_id] => 1
      )

     *      */

    /**
     * 周期订单
     *  @param $attributes [
     *  string $order_ip 下单IP地址 必填
     *  integer $order_service_item_id 服务类型 商品id 必填
     *  integer $order_src_id 订单来源id 必填
     *  string $channel_id 下单渠道 必填
     *  int $address_id 客户地址id 必填
     *  int $customer_id 客户id 必填
     *  int $admin_id 操作人id  1系统 2客户 3阿姨 必填
     *  int $order_pay_type 支付方式 1现金 2线上 3第三方 必填
     *  int $order_is_use_balance 是否使用余额 0否 1是
     *  string $order_booked_worker_id 指定阿姨id
     *  string $order_customer_need 客户需求
     *  string $order_customer_memo 客户备注
     *  string $order_flag_sys_assign 是否系统指派
     *  int $order_flag_change_booked_worker 是否可更换指定阿姨
     *  string $order_cs_memo 客服备注
     * ]
     * @param $booked_list [
     *      [
     *          int $order_booked_begin_time 预约开始时间 必填
     *          int $order_booked_code 预约时长 必填
     *          int $order_booked_end_time 预约结束时间 必填
     *          int $coupon_id 优惠券id
     *      ]
     * ]
     * @return array
     */
    public static function createNewBatch($attributes, $booked_list)
    {

        $attributes_keys = [
            'order_ip','order_service_item_id','order_src_id','channel_id', 'address_id',
            'customer_id','admin_id','order_pay_type',
            'coupon_id','order_is_use_balance','order_booked_worker_id','order_pop_order_code',
            'order_pop_group_buy_code','order_pop_order_money','order_customer_need','order_customer_memo',
            'order_flag_sys_assign','order_cs_memo','order_flag_change_booked_worker'
        ];
        $attributes_required = [
            'order_ip','order_service_item_id','order_src_id','channel_id', 'address_id', 'customer_id','admin_id','order_pay_type'
        ];
        foreach($attributes as $k=>$v){
            if(!in_array($k,$attributes_keys)){
                unset($attributes[$k]);
            }
        }
        foreach($attributes_required as $v){
            if(!isset($attributes[$v])){
                return ['status' => false, 'errors' => $v.'为必填项！'];
            }
        }
        $attributes['order_flag_sys_assign'] = !isset($attributes['order_flag_sys_assign'])?1:$attributes['order_flag_sys_assign'];
        $transact = static::getDb()->beginTransaction();
        //如果指定阿姨则是周期订单分配周期订单号否则分配批量订单号

        if (isset($attributes['order_booked_worker_id']) && $attributes['order_booked_worker_id'] > 0) {
            $attributes['order_batch_code'] = OrderTool::createOrderCode('Z');
            $attributes['order_parent_id'] = 0;
            $attributes['order_is_parent'] = 1; //周期订单为父子订单
        } else {
            $attributes['order_batch_code'] = OrderTool::createOrderCode('P');
            $attributes['order_parent_id'] = 0;
            $attributes['order_is_parent'] = 0; //批量订单为普通订单
        }

        $customer = Customer::getCustomerById($attributes['customer_id']);
        if(!empty($customer)) {
            $attributes['order_customer_phone'] = $customer->customer_phone;
            $attributes['customer_is_vip'] = $customer->customer_is_vip;
        }else{
            return ['status' => false, 'errors' => '没有获取到用户信息！'];
        }

        $customer_balance = 0;
        //如果客户选择使用余额则去获取客户余额
        if(!empty($attributes['order_is_use_balance'])) {
            try {
                $customer = Customer::getCustomerInfo($attributes['order_customer_phone']);
                $customer_balance = $customer['customer_balance'];
            } catch (Exception $e) {
                return ['status' => false, 'errors' => '创建时获客户余额信息失败！'];
            }
        }

        //在线支付初始化
        $orderExtPay = 0;
        foreach ($booked_list as $v) {
            $order = new Order();
            $booked = [
                'order_booked_begin_time'=>$v['order_booked_begin_time'],
                'order_booked_count'=>$v['order_booked_count'],
                'order_booked_end_time'=>$v['order_booked_end_time'],
                'coupon_id'=>isset($v['coupon_id'])?$v['order_booked_end_time']:0
            ];
            if (!$order->_create($attributes + $booked, $transact,$customer_balance)) {
                $transact->rollBack();

                return ['status' => false, 'errors' => $order->errors];
            } else {
                if ($attributes['order_parent_id'] == 0 && $attributes['order_is_parent'] == 1) {
                    //第一个订单为父订单其余为子订单
                    $attributes['order_parent_id'] = $order->id;
                    $attributes['order_is_parent'] = 0;
                    $orderExtPay += $order->orderExtPay->order_pay_money;
                }
            }
        }

        $transact->commit();

        if( $orderExtPay == 0)
        {
            //交易记录
            if (PaymentCustomerTransRecord::analysisRecord($attributes['order_batch_code'], $attributes['channel_id'], 'order_pay',2)) {
                OrderStatus::_batchPayment($attributes['order_batch_code'],$attributes['admin_id']);
            }
        }
        return ['status' => true, 'batch_code' => $attributes['order_batch_code']];
    } 

    /**
     * 在线支付完后调用
     * @user 李胜强
     * @param $order_id int 订单id
     * @param $pay_channel_id int  支付渠道id
     * @param $order_pay_channel_name string 支付渠道名称
     * @param $order_pay_flow_num string 支付流水号
     * @return bool
     */
    public static function isPaymentOnline($order_id, $pay_channel_id, $order_pay_channel_name, $order_pay_flow_num)
    {
        $order = OrderSearch::getOne($order_id);
        $order->setAttributes([
            'order_pay_type' => OrderExtPay::ORDER_PAY_TYPE_ON_LINE,
            'admin_id' => Order::ADMIN_CUSTOMER,
            'pay_channel_id' => $pay_channel_id,
            'order_pay_channel_name' => $order_pay_channel_name,
            'order_pay_flow_num' => $order_pay_flow_num
        ]);
        return OrderStatus::_payment($order, ['OrderExtPay']);
    }

    /**
     * 批量支付回调接口
     * @user 李胜强
     * @param $batch_code int 订单id
     * @param $pay_channel_id int  支付渠道id
     * @param $order_pay_channel_name string 支付渠道名称
     * @param $order_pay_flow_num string 支付流水号
     * @return bool
     */
    public static function isBatchPaymentOnline($batch_code, $pay_channel_id, $order_pay_channel_name, $order_pay_flow_num)
    {
        return OrderStatus::_batchPayment($batch_code,Order::ADMIN_CUSTOMER , $pay_channel_id, $order_pay_channel_name, $order_pay_flow_num);
    }

    /**
     * 智能指派失败
     * @param $order_id
     * @return bool
     */
    public static function sysAssignUndone($order_id)
    {
        $order = OrderSearch::getOne($order_id);
        $order->admin_id = Order::ADMIN_SYSTEM;
        if ($order->orderExtStatus->order_status_dict_id == OrderStatusDict::ORDER_SYS_ASSIGN_START) { //开始系统指派的订单
            if (OrderStatus::_sysAssignUndone($order, [])) {
                //把订单从订单池中移除
                OrderPool::remOrder($order_id);
                return true;
            }
        }
        return false;
    }

    /**
     * ivr指派成功 阿姨接单
     * @param $order_id
     * @param $worker_phone
     * @return array
     */
    public static function ivrAssignDone($order_id, $worker_phone)
    {
        $worker = Worker::getWorkerInfoByPhone($worker_phone);
        $assign_type = OrderExtWorker::ASSIGN_TYPE_IVR;
        return self::assignDone($order_id, $worker, Order::ADMIN_WORKER, $assign_type);
    }

    /**
     * 系统指派成功 阿姨接单
     * @param $order_id
     * @param $worker_id
     * @return array
     */
    public static function sysAssignDone($order_id, $worker_id)
    {
        $worker = Worker::getWorkerInfo($worker_id);
        $assign_type = OrderExtWorker::ASSIGN_TYPE_WORKER;
        return self::assignDone($order_id, $worker, Order::ADMIN_WORKER, $assign_type);
    }

    /**
     * 人工指派失败
     * @param $order_id
     * @param $admin_id
     * @return array|bool
     */
    public static function manualAssignUndone($order_id, $admin_id = Order::ADMIN_SYSTEM)
    {
        $order = OrderSearch::getOne($order_id);
        if ($order->orderExtFlag->order_flag_send == 3) { //小家政和客服都无法指派出去
            OrderPool::remOrderForWorkerPushList($order->id, true); //永久从接单大厅中删除此订单
            if($order->order_is_parent == 1){
                $orders = OrderSearch::getBatchOrder($order->order_batch_code);
                $transact = static::getDb()->beginTransaction();
                foreach($orders as $order) {
                    $order->order_flag_lock = 0;
                    $order->admin_id = $admin_id;
                    $result = OrderStatus::_manualAssignUndone($order, ['OrderExtFlag'],$transact);
                    if(!$result){
                        $transact->rollBack();
                        return $result;
                    }
                }
                $transact->commit();
                return $result;
            }else {
                $order->order_flag_lock = 0;
                $order->admin_id = $admin_id;
                return OrderStatus::_manualAssignUndone($order, ['OrderExtFlag']);
            }
        } else {//客服或小家政还没指派过则进入待人工指派的状态
            OrderPool::reAddOrderToWorkerPushList($order_id); //重新添加到接单大厅
            if($order->order_is_parent == 1){
                $orders = OrderSearch::getBatchOrder($order->order_batch_code);
                $transact = static::getDb()->beginTransaction();
                foreach($orders as $order) {
                    $order->order_flag_lock = 0;
                    $order->admin_id = $admin_id;
                    $result = OrderStatus::_payment($order, ['OrderExtFlag'],$transact);
                    if(!$result){
                        $transact->rollBack();
                        return $result;
                    }
                }
                $transact->commit();
                return $result;
            }else {
                $order->order_flag_lock = 0;
                $order->admin_id = $admin_id;
                $order->order_flag_sys_assign = 0;
                return OrderStatus::_payment($order, ['OrderExtFlag']);
            }
        }
    }


    /**
     * 人工指派成功
     * @param $order_id
     * @param $worker_id
     * @param $admin_id
     *  @param bool $is_cs
     * @return array
     */
    public static function manualAssignDone($order_id, $worker_id, $admin_id, $is_cs = false)
    {
        $assign_type = $is_cs ? OrderExtWorker::ASSIGN_TYPE_CS : OrderExtWorker::ASSIGN_TYPE_SHOP;
        $worker = Worker::getWorkerInfo($worker_id);
        return self::assignDone($order_id, $worker, $admin_id, $assign_type);
    }

    /**
     * 指派成功
     * @param $order_id
     * @param $worker
     * @param $admin_id
     * @param $assign_type
     * @return array
     */
    public static function assignDone($order_id, $worker, $admin_id, $assign_type)
    {
        $result = false;
        $order = OrderSearch::getOne($order_id);
        $orderids = [];
        //阿姨服务时间是否冲突
        $conflict = OrderSearch::workerOrderExistsConflict($worker['id'], $order->order_booked_begin_time, $order->order_booked_end_time);
        if($order->order_is_parent==1){ //如果是周期订单
            $child_list = OrderSearch::getChildOrder($order_id);
            foreach($child_list as $child){
                $conflict += OrderSearch::workerOrderExistsConflict($worker['id'], $child->order_booked_begin_time, $child->order_booked_end_time);
            }
        }
        if ($order->orderExtFlag->order_flag_lock > 0 && $order->orderExtFlag->order_flag_lock != $admin_id && time() - $order->orderExtFlag->order_flag_lock_time < Order::MANUAL_ASSIGN_lONG_TIME) {
            $order->addError('id', '订单正在进行人工指派！');
        } elseif ($conflict > 0) {
            $order->addError('id', '阿姨服务时间冲突！');
        } elseif ($order->orderExtWorker->worker_id > 0) {
            $order->addError('id', '订单已经指派阿姨！');
        } else {
            $transact = static::getDb()->beginTransaction();
            $result = self::_assignDone($order, $worker, $admin_id, $assign_type,$transact);
            $orderids[] = $order->id;
            //处理阿姨排班表
            Worker::operateWorkerOrderInfoToRedis($worker['id'],1,$order->id,$order->order_booked_count,$order->order_booked_begin_time,$order->order_booked_end_time);
            if($result && $order->order_is_parent==1) { //如果是周期订单
                foreach ($child_list as $child) {
                    $result = self::_assignDone($child, $worker, $admin_id, $assign_type, $transact);
                    $orderids[] = $child->id;
                    if(!$result){
                        break;
                    }
                    //处理阿姨排班表
                    Worker::operateWorkerOrderInfoToRedis($worker['id'],1,$child->id,$child->order_booked_count,$child->order_booked_begin_time,$child->order_booked_end_time);
                }
            }

            if($result && $order->order_src_id == OrderSrc::ORDER_SRC_POP){
                $result = OrderPop::assignDoneToPop($order); //第三方同步失败则取消失败
            }

            if($result) {
                OrderPool::remOrderForWorkerPushList($order->id, true); //永久从接单大厅中删除此订单
                WorkerStat::updateWorkerStatOrderNum($worker['id'], 1); //更新阿姨接单数量 第二个参数是阿姨的接单次数
                $transact->commit();
                OrderMsg::assignDone($order->id); //指派成功发送通知
            }else{
                $transact->rollBack();
                foreach($orderids as $orderid) {
                    //失败后删除阿姨的订单信息
                    Worker::deleteWorkerOrderInfoToRedis($worker['id'], $orderid);
                }
            }
        }
        return ['status' => $result, 'errors' => $order->errors];
    }

    /**
     * 内部方法供指派成功调用
     * @param $order
     * @param $worker
     * @param $admin_id
     * @param $assign_type
     * @param $transact
     * @return bool
     */
    private static function _assignDone(&$order, $worker, $admin_id, $assign_type,$transact)
    {
        $order->order_flag_lock = 0;
        $order->worker_id = $worker['id'];
        $order->worker_type_id = $worker['worker_type'];
        $order->order_worker_phone = $worker['worker_phone'];
        $order->order_worker_name = $worker['worker_name'];
        $order->order_worker_type_name = $worker['worker_type_description'];
        $order->shop_id = $worker["shop_id"];
        $order->order_worker_shop_name = $worker["shop_name"];
        $order->order_worker_assign_time = YII_BEGIN_TIME;
        $order->order_worker_assign_type = $assign_type; //接单方式
        $order->admin_id = $admin_id;
        if ($admin_id > 3) { //大于3属于人工操作
            $result = OrderStatus::_manualAssignDone($order, ['OrderExtFlag', 'OrderExtWorker'],$transact);
        } elseif ($order->orderExtStatus->order_status_dict_id == OrderStatusDict::ORDER_SYS_ASSIGN_START) { //当前状态如果是开始智能派单 就到智能派单成功 否则 到阿姨自助接单
            $result = OrderStatus::_sysAssignDone($order, ['OrderExtFlag', 'OrderExtWorker'],$transact);
        } else {
            $result = OrderStatus::_workerBindOrder($order, ['OrderExtFlag', 'OrderExtWorker'],$transact);
        }
        return $result;
    }

    /**
     * 开始服务
     * @param $order_id
     * @return bool
     */
    public static function serviceStart($order_id)
    {
        $order = OrderSearch::getOne($order_id);
        $order->admin_id = 1;
        return OrderStatus::_serviceStart($order);
    }

    /**
     * 服务完成
     * TODO 添加常用阿姨
     * @param $order_id
     * @return bool
     */
    public static function serviceDone($order_id)
    {
        $transact = static::getDb()->beginTransaction();
        $order = OrderSearch::getOne($order_id);
        $order->admin_id = 1;
        $result = OrderStatus::_serviceDone($order,[],$transact);
        if($result && $order->order_src_id == OrderSrc::ORDER_SRC_POP){
            $result = OrderPop::serviceDoneToPop($order); //第三方同步失败则取消失败
        }
        if($result) {
            $transact->commit();
            OrderMsg::serviceDone($order); //发送通知
        }else{
            $transact->rollBack();
        }
        return $result;
    }

    /**
     * 评价接口
     * @param $order_id
     * @param $admin_id
     * @return bool
     */
    public static function customerAcceptDone($order_id,$admin_id=Order::ADMIN_CUSTOMER)
    {
        $order = OrderSearch::getOne($order_id);
        $order->admin_id = $admin_id;
        return OrderStatus::_customerAcceptDone($order);
    }

    /**
     * 订单已对账
     * @param $order_id
     * @param $admin_id
     * @return bool
     */
    public static function checked($order_id,$admin_id)
    {
        $order = OrderSearch::getOne($order_id);
        $order->admin_id = $admin_id;
        $order->order_flag_is_checked = 1;
        return $order->doSave(['OrderExtFlag']);
    }

    /**
     * 订单完成结算
     * @param $order_id
     * @param $admin_id
     * @return bool
     */
    public static function payoffDone($order_id,$admin_id)
    {
        $order = OrderSearch::getOne($order_id);
        $order->admin_id = $admin_id;
        return OrderStatus::_payoffDone($order);
    }

    /**
     * 取消订单
     * @param $order_id
     * @param $admin_id
     * @param $cause_id
     * @param string $memo
     * @return bool
     */
    public static function cancelByOrderId($order_id, $admin_id, $cause_id, $memo = ''){
        $order = OrderSearch::getOne($order_id);
        return self::_cancelOrder($order, $admin_id, $cause_id, $memo);
    }

    /**
     * 取消订单
     * @param $order_code
     * @param $admin_id
     * @param $cause_id
     * @param string $memo
     * @return bool
     */
    public static function cancelByOrderCode($order_code, $admin_id, $cause_id, $memo = ''){
        $order = OrderSearch::getOneByCode($order_code);
        return self::_cancelOrder($order, $admin_id, $cause_id, $memo);
    }

    /**
     * 取消订单
     * @param $order
     * @param $admin_id
     * @param $cause_id
     * @param $memo
     * @return bool
     */
    private static function _cancelOrder($order, $admin_id, $cause_id, $memo = '')
    {

        $order->admin_id = $admin_id;
        if($cause_id>0) {
            $order->order_cancel_cause_id = $cause_id;
            $order->order_cancel_cause_detail = OrderOtherDict::getName($cause_id);
        }
        $order->order_cancel_cause_memo = $memo;
        $current_status = $order->orderExtStatus->order_status_dict_id;
        if (in_array($current_status, [  //只有在以下状态下才可以取消订单
                    OrderStatusDict::ORDER_INIT,
                    OrderStatusDict::ORDER_WAIT_ASSIGN,
                    OrderStatusDict::ORDER_SYS_ASSIGN_DONE,
                    OrderStatusDict::ORDER_SYS_ASSIGN_UNDONE,
                    OrderStatusDict::ORDER_MANUAL_ASSIGN_DONE,
                    OrderStatusDict::ORDER_MANUAL_ASSIGN_UNDONE,
                ])) {
            OrderPool::remOrderForWorkerPushList($order->id, true); //永久从接单大厅中删除此订单
            $transact = static::getDb()->beginTransaction();
            $result = OrderStatus::_cancel($order,[],$transact);
            if ($result && $order->orderExtPay->order_pay_type == OrderExtPay::ORDER_PAY_TYPE_ON_LINE && $current_status != OrderStatusDict::ORDER_INIT
                || $current_status == OrderStatusDict::ORDER_INIT && $cause_id==OrderOtherDict::NAME_CANCEL_ORDER_CUSTOMER_PAY_FAILURE ) {
                //调高峰的退款接口
                $finance_refund_add = new FinanceRefundadd();
                $result = $finance_refund_add->add($order);
                if(in_array($current_status, [  //如果处于以下状态则去除排班表中占用的时间
                    OrderStatusDict::ORDER_SYS_ASSIGN_DONE,
                    OrderStatusDict::ORDER_MANUAL_ASSIGN_DONE,
                    OrderStatusDict::ORDER_WORKER_BIND_ORDER
                ]) && $result){
                    $result = Worker::deleteWorkerOrderInfoToRedis($order->orderExtWorker->worker_id, $order->id);
                }
            }elseif($result && $order->order_src_id == OrderSrc::ORDER_SRC_POP){
                $result = OrderPop::cancelToPop($order); //第三方同步失败则取消失败
            }
            if($result){
                $transact->commit();
                OrderMsg::cancel($order); //取消订单发送通知
            }else{
                $transact->rollBack();
            }
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 客户删除订单
     * @param $order_id
     * @param $admin_id
     * @return bool
     */
    public static function customerDel($order_id, $admin_id = Order::ADMIN_CUSTOMER)
    {
        $order = OrderSearch::getOne($order_id);
        $order->order_customer_hidden = 1;
        $order->admin_id = $admin_id;
        return $order->doSave(['OrderExtCustomer']);
    }

    /**
     * 创建订单
     * @param $attributes
     * @param $transact
     * @param $customer_balance 客户余额
     * @return bool
     */
    private function _create($attributes, $transact = null,&$customer_balance)
    {
        $this->setAttributes($attributes);
        $status_from = OrderStatusDict::findOne(OrderStatusDict::ORDER_INIT); //创建订单状态
        $status_to = OrderStatusDict::findOne(OrderStatusDict::ORDER_INIT); //初始化订单状态
        $order_code = OrderTool::createOrderCode(); //创建订单号



        try {
            $address = CustomerAddress::getAddress($this->address_id);
        } catch (Exception $e) {
            $this->addError('order_address', '创建时获取地址异常！');
            return false;
        }
        try {
            $goods = self::getGoods($address['customer_address_longitude'], $address['customer_address_latitude'], $attributes['order_service_item_id']);
        } catch (Exception $e) {
            $this->addError('order_service_item_name', '创建时获商品信息异常！');
            return false;
        }
        if (empty($goods)) {
            $this->addError('order_service_item_name', '创建时获商品信息失败！');
            return false;
        } elseif ($goods['code'] >= 500) {
            $this->addError('order_service_item_name', $goods['msg']);
            return false;
        }else{
            $goods = $goods['data'];
        }
        $this->setAttributes([
            'order_unit_money' => $goods['operation_shop_district_goods_price'], //单价
            'order_service_item_name' => $goods['operation_shop_district_goods_name'], //商品名称
            'order_service_type_id' => $goods['operation_category_id'], //品类ID
            'order_service_type_name' => $goods['operation_category_name'], //品类名称
        ]);
        $this->setAttributes([
            'order_money' => $this->order_unit_money * $this->order_booked_count, //订单总价
            'city_id' => $address['operation_city_id'],
            'district_id' => $goods['district_id'],
            'order_address' => (empty($address['operation_province_name'])?'': $address['operation_province_name'].',')
                . $address['operation_city_name'] . ','
                . $address['operation_area_name'] . ','
                . $address['customer_address_detail'], //地址信息
            'order_lat' => $address['customer_address_latitude'],
            'order_lng' => $address['customer_address_longitude']
        ]);

        if(in_array($this->order_service_item_name,Yii::$app->params['order']['USE_ORDER_FLOW_SERVICE_ITEMS'])) {//TODO 判断是否使用订单流程
            $range = date('G:i', $this->order_booked_begin_time) . '-' . date('G:i', $this->order_booked_end_time);
            $ranges = $this->getThisOrderBookedTimeRangeList();
            if (!in_array($range, $ranges)) {
                $this->addError('order_booked_begin_time', "该时间段暂时没有可用阿姨！");
                return false;
            }
        }

        if ($this->order_pay_type == OrderExtPay::ORDER_PAY_TYPE_POP) { //第三方预付
            $this->order_pop_operation_money = $this->order_money - $this->order_pop_order_money; //渠道运营费
        } elseif ($this->order_pay_type == OrderExtPay::ORDER_PAY_TYPE_ON_LINE) {//线上支付
            $this->order_pay_money = $this->order_money; //支付金额
            if (!empty($this->coupon_id)) {//是否使用了优惠券
                $coupon = self::getCouponById($this->coupon_id);
                if(!empty($coupon)) {
                    $this->order_use_coupon_money = $coupon['coupon_price'];
                    $this->order_coupon_code = $coupon['coupon_code'];
                    $this->order_pay_money -= $this->order_use_coupon_money;
                }else{
                    $this->addError('coupon_id', '获取优惠券信息失败！');
                    return false;
                }
            }
            if ($this->order_is_use_balance == 1) {
                if ($customer_balance < $this->order_pay_money) { //用户余额小于需支付金额
                    $this->order_use_acc_balance = $customer_balance; //使用余额为用户余额
                } else {
                    $this->order_use_acc_balance = $this->order_pay_money; //使用余额为需支付金额
                }
                $customer_balance -= $this->order_use_acc_balance; //用户余额减去使用余额
                $this->order_pay_money -= $this->order_use_acc_balance;
            }
        }


        $this->setAttributes([
            //创建订单时服务卡是初始状态
            'card_id' => 0,
            'order_use_card_money' => 0,
            //为以下数据赋初始值
            'order_code' => $order_code,
            'order_before_status_dict_id' => $status_from->id,
            'order_before_status_name' => $status_from->order_status_name,
            'order_status_dict_id' => $status_to->id,
            'order_status_name' => $status_to->order_status_name,
            'order_status_boss' => $status_to->order_status_boss,
            'order_status_customer' => $status_to->order_status_customer,
            'order_status_worker' => $status_to->order_status_worker,

            'order_src_name' => $this->getOrderSrcName($this->order_src_id),
            'order_channel_name' => $this->getOrderChannelList($this->channel_id),
            'order_flag_send' => 0, //'指派不了 0可指派 1客服指派不了 2小家政指派不了 3都指派不了',
            'order_flag_urgent' => 0, //加急 数字越大约紧急
            'order_flag_exception' => 0, //异常标识
            'order_flag_lock' => 0,
            'worker_id' => 0,
            'worker_type_id' => 0,
            'order_worker_send_type' => 0,
            'comment_id' => 0,
            'order_customer_hidden' => 0,
            'order_pop_pay_money' => 0, //第三方结算金额
            'shop_id' => 0,
            'order_worker_type_name' => '',
            'pay_channel_id' => 0, //支付渠道id
            'order_pay_channel_name' => '', //支付渠道
            'order_pay_flow_num' => '', //支付流水号
            'invoice_id' => 0, //发票id 用户需求中有开发票就绑定发票id
            'checking_id' => 0,
            'isdel' => 0,
        ]);

        return $this->doSave(['OrderExtCustomer', 'OrderExtFlag', 'OrderExtPay', 'OrderExtPop', 'OrderExtStatus', 'OrderExtWorker', 'OrderStatusHistory'], $transact);
    }

    /**
     * 更新订单
     * @param $attributes
     * @param $transact
     * @return bool
     */
    public function modify($attributes, $transact = null)
    {
        $status = [
            OrderStatusDict::ORDER_INIT, // = 1已创建
            OrderStatusDict::ORDER_WAIT_ASSIGN, // = 2待指派
            OrderStatusDict::ORDER_SYS_ASSIGN_START, // = 3智能指派开始
            OrderStatusDict::ORDER_SYS_ASSIGN_DONE, // = 4智能指派完成
            OrderStatusDict::ORDER_SYS_ASSIGN_UNDONE, // = 5未完成智能指派 待人工指派
            OrderStatusDict::ORDER_MANUAL_ASSIGN_START, // = 6开始人工指派
            OrderStatusDict::ORDER_MANUAL_ASSIGN_DONE, // = 7完成人工指派
            OrderStatusDict::ORDER_MANUAL_ASSIGN_UNDONE, // = 8未完成人工指派，如果客服和小家政都未完成人工指派则去响应，否则重回待指派状态。
            OrderStatusDict::ORDER_WORKER_BIND_ORDER, // = 9阿姨自助抢单
        ];

        //1:获取订单状态
        if( !in_array($this->orderExtStatus->order_status_dict_id,$status))
        {
            $this->addError('order_status', '当前订单状态不可更新,当前订单状态');
            return false;
        }

        //2:载入传入参数
        $this->setAttributes($attributes);

        //创建地址信息
        if( !empty($attributes['address_id']) )
        {
            try {
                $address = CustomerAddress::getAddress($attributes['address_id']);
                $addressInfo = $address['operation_province_name'] . ',' . $address['operation_city_name'] . ',' . $address['operation_area_name'] . ',' . $address['customer_address_detail'] . ',' . $address['customer_address_nickname'] . ',' . $address['customer_address_phone']; //地址信息
                $this->setAttributes([
                    'address_id'=>$address['id'],
                    'order_address'=>$addressInfo,
                ]);
            } catch (Exception $e) {
                $this->addError('order_address', '创建时获取地址异常！');
                return false;
            }
        }

        //获取服务日期时间段是否可用
        if( !empty($attributes['order_booked_begin_time']) && !empty($attributes['order_booked_time_range']) )
        {
            $time = explode('-',$attributes['order_booked_time_range']);
            $attr['order_booked_begin_time'] = strtotime($attributes['order_booked_begin_time'].' '.$time[0].':00');
            $attr['order_booked_end_time'] = strtotime(($time[1]=='24:00')?date('Y-m-d H:i:s',strtotime($attributes['order_booked_begin_time'].'00:00:00 +1 days')):$attributes['order_booked_begin_time'].' '.$time[1].':00');
            //判断订单已经指定阿姨,检测阿姨时间段是否可用
            if( !empty($this->orderExtWorker->worker_id) ){
                $checkWorkerTime = worker::checkWorkerTimeIsDisabled($this->district_id,$this->OrderExtWorker->worker_id,$attributes['order_booked_begin_time'],$attributes['order_booked_end_time']);
                if(empty($checkWorkerTime))
                {
                    return false;
                }
            }

            //设置参数
            $this->setAttributes([
                'order_booked_begin_time'=>$attr['order_booked_begin_time'],
                'order_booked_end_time'=>$attr['order_booked_end_time'],
            ]);

            /**
             * 获取阿姨时间排班表
             * @param int $district_id 商圈id
             * @param int $serverDurationTime 服务时长
             * @param string $beginTime 排班表开始时间 默认今天
             * @param int $timeLineLength 排班表长度 默认返回7天的排班表
             * @param string $worker_id 阿姨id 通过阿姨id获取指定阿姨的排班表 默认返回所有阿姨排班表
             * @return array
             */
            //worker::getWorkerTimeLine();
        }
        $this->setAttributes([
            'admin_id'=>Yii::$app->user->id
        ]);

        //3:修改订单信息
        return $this->doSave(['OrderExtCustomer', 'OrderExtFlag', 'OrderExtPay', 'OrderExtPop', 'OrderExtStatus', 'OrderExtWorker', 'OrderStatusHistory'], $transact);
    }


    /**
     * 获取订单来源名称
     * @param $id
     * @return string
     */
    public function getOrderSrcName($id)
    {
        return OrderSrc::findOne($id)->order_src_name;
    }

    /**
     * 获取订单渠道
     * @param int $channel_id
     * @return array|bool
     */
    public function getOrderChannelList($channel_id = 0)
    {
        $list = FinanceOrderChannel::get_order_channel_list();
        $channel = ArrayHelper::map($list, 'id', 'finance_order_channel_name');
        return $channel_id == 0 ? $channel : (isset($channel[$channel_id]) ? $channel[$channel_id] : false);
    }

    /**
     * 根据经纬度获取商品信息
     * @param $longitude
     * @param $latitude
     * @param int $goods_id
     * @return array
     */
    public static function getGoods($longitude, $latitude, $goods_id = 0)
    {
        $shop_district_info = OperationShopDistrictCoordinate::getCoordinateShopDistrictInfo($longitude, $latitude);
        if (empty($shop_district_info)) {
            return ['code' => 502, 'msg' => '该地址没有商圈，获取服务项目失败！'];
        } else {
            if ($goods_id == 0) {
                $goods = OperationShopDistrictGoods::getShopDistrictGoodsList($shop_district_info['operation_city_id'], $shop_district_info['operation_shop_district_id']);
            }else{
                $goods = OperationShopDistrictGoods::getShopDistrictGoodsInfo($shop_district_info['operation_city_id'], $shop_district_info['operation_shop_district_id'],$goods_id);
            }
            if (empty($goods)) {
                return ['code' => 501, 'msg' => '没有匹配到服务项目！'];
            } else if($goods_id == 0){
                return ['code' => 200, 'data' => $goods, 'district_id' => $shop_district_info['operation_shop_district_id']];
            } else {
                $goods['district_id'] = $shop_district_info['operation_shop_district_id'];
                return [ 'code'=> 200,'data'=> $goods ];
            }
        }
    }

    /**
     * 获取优惠券
     * @param $id
     * @return mixed
     */
    public static function getCouponById($id)
    {
        $result = CouponRule::getCouponBasicInfoById($id);
        if(isset($result['is_status']) && $result['is_status']==1){
            return $result['data'];
        }
        return false;
    }

    /**
     * 获取服务卡
     * @param $id
     * @return mixed
     */
    public function getCardById($id)
    {
        $card = [
            1 => [
                "id" => 1,
                "card_code" => "1234567890",
                "card_money" => 1000
            ],
            2 => [
                "id" => 2,
                "card_code" => "9876543245",
                "card_money" => 3000
            ],
            3 => [
                "id" => 3,
                "card_code" => "3840959205",
                "card_money" => 5000
            ]
        ];
        return $card[$id];
    }

    /**
     * 获取已上线商圈列表
     * @date 2015-10-26
     * @author peak pan
     * @return array
      */
    public static function getDistrictList()
    {
        $districtList = OperationShopDistrict::getCityShopDistrictList();
        return $districtList ? ArrayHelper::map($districtList, 'id', 'operation_shop_district_name') : [];
    }


    /*
     * 获取订单状态列表
     */

    public static function getStatusList($status = '')
    {
        $statusAC = OrderStatusDict::find();
        if (isset($status) && is_array($status)) {
            $statusList = $statusAC->where(['in', 'id', $status])->asArray()->all();
        } else {
            $statusList = $statusAC->where(['not in', 'id', [OrderStatusDict::ORDER_MANUAL_ASSIGN_DONE,OrderStatusDict::ORDER_SYS_ASSIGN_DONE]])->asArray()->all();
        }
        return $statusList ? ArrayHelper::map($statusList, 'id', 'order_status_boss') : [];
    }

    /*
     * 获取服务项目表
     */
    
    public static function getServiceTypes()
    {
        $list = OperationCategory::find()->asArray()->all();
        return $list ? ArrayHelper::map($list, 'id', 'operation_category_name') : [];
    }
    
    /*
     * 获取服务项目表
     */

    public static function getServiceItems()
    {
        $list = OperationGoods::find()->asArray()->all();
        return $list ? ArrayHelper::map($list, 'id', 'operation_goods_name') : [];
    }

    /*
     * 返回订单预订日期的字符串，如2015-09-25
     */

    public function getOrderBookedDate()
    {
        return date('Y-m-d', $this->order_booked_begin_time);
    }

    /*
     * 返回订单预订的时间范围字符串，如08:00-10:00
     */

    public function getOrderBookedTimeArrange()
    {
        return date('H:i', $this->order_booked_begin_time) . '-' . date('H:i', $this->order_booked_end_time);
    }

    /**
     * 根据本订单获取可选下单时间列表
     * @return array
     */
    public function getThisOrderBookedTimeRangeList()
    {
        $time_range = self::getOrderBookedTimeRangeList($this->district_id,$this->order_booked_count,date('Y-m-d',$this->order_booked_begin_time),1);
        $order_booked_time_range = [];
        foreach($time_range[0]['timeline'] as $range){
            if($range['enable']) {
                $order_booked_time_range[$range['time']] = $range['time'];
            }
        }
        return $order_booked_time_range;
    }

    /**
     * 获取可下单时间列表
     * @param int $district_id
     * @param int $range
     * @param int $date
     * @param int $days
     * @return array
     */
    public static function getOrderBookedTimeRangeList($district_id=0,$range = 2,$date=0,$days=1)
    {
        if($district_id>0) {
            $date = strtotime($date);
            return Worker::getWorkerTimeLine($district_id, $range, $date, $days);
        }
        $order_booked_time_range = [];
        for ($i = 8; $i <= 18; $i++) {
            $hour = str_pad($i, 2, '0', STR_PAD_LEFT);
            $hour2 = str_pad($i + intval($range), 2, '0', STR_PAD_LEFT);
            $minute = ($range - intval($range) == 0) ? '00' : '30';
            $order_booked_time_range["{$hour}:00-{$hour2}:{$minute}"] = "{$hour}:00-{$hour2}:{$minute}";
        }
        return $order_booked_time_range;
    }

}
