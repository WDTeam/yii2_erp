<?php

/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/9/23
 * Time: 18:30
 */

namespace core\models\order;

use core\models\operation\OperationShopDistrictGoods;
use core\models\operation\OperationShopDistrictCoordinate;
use core\models\customer\Customer;
use core\models\customer\CustomerAddress;
use core\models\payment\Payment;
use core\models\worker\Worker;
use core\models\operation\OperationShopDistrict;
use core\models\operation\OperationGoods;

use dbbase\models\order\OrderExtFlag;
use dbbase\models\order\OrderExtPay;
use dbbase\models\order\OrderExtWorker;
use dbbase\models\order\Order as OrderModel;
use dbbase\models\order\OrderStatusDict;
use dbbase\models\order\OrderExtCustomer;
use dbbase\models\order\OrderSrc;
use dbbase\models\finance\FinanceOrderChannel;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "{{%order}}".
 *
 * @property string $id
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
 * @property string $order_ip
 * @property integer $order_service_type_id
 * @property string $order_service_type_name
 * @property integer $order_src_id
 * @property string $order_src_name
 * @property string $channel_id
 * @property string $order_channel_name
 * @property string $order_unit_money
 * @property string $order_money
 * @property string $order_booked_count
 * @property string $order_booked_begin_time
 * @property string $order_booked_end_time
 * @property string $address_id
 * @property string $order_address
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
    const ORDER_ASSIGN_WORKER_LOCK = 'ORDER_ASSIGN_WORKER_LOCK';

    /**
     * 创建新订单
     * @param $attributes [
     *  string $order_ip 下单IP地址 必填
     *  integer $order_service_type_id 服务类型 商品id 必填
     *  integer $order_src_id 订单来源id 必填
     *  string $channel_id 下单渠道 必填
     *  int $order_booked_begin_time 预约开始时间 必填
     *  int $order_booked_end_time 预约结束时间 必填
     *  int $address_id 客户地址id 必填
     *  int $customer_id 客户id 必填
     *  string $order_customer_phone 客户手机号 必填
     *  int $admin_id 操作人id 0客户 1系统 必填
     *  int $order_pay_type 支付方式 1现金 2线上 3第三方 必填
     *  int $coupon_id 优惠券id
     *  int $order_is_use_balance 是否使用余额 0否 1是 必填
     *  string $order_booked_worker_id 指定阿姨id
     *  string $order_pop_order_code 第三方订单号
     *  string $order_pop_group_buy_code 第三方团购号
     *  int $order_pop_order_money 第三方预付金额
     *  string $order_customer_need 客户需求
     *  string $order_customer_memo 客户备注
     * ]
     * @return bool
     */
    public function createNew($attributes)
    {
        $attributes['order_parent_id'] = 0;
        $attributes['order_is_parent'] = 0;
        if($this->_create($attributes)) {
            $order = $this->attributes;
            $order['order_id'] = $this->id;
            $order_model = OrderSearch::getOne($this->id);
            switch ($this->orderExtPay->order_pay_type) {
                case OrderExtPay::ORDER_PAY_TYPE_OFF_LINE://现金支付
                    //交易记录
                    $order['payment_customer_trans_record_cash'] = $this->order_money;
                    $order['payment_source'] = 20;
                    if (Payment::cashPay($order)) {
                        $order_model->admin_id = $attributes['admin_id'];
                        OrderStatus::_payment($order_model, ['OrderExtPay']);
                    }
                    break;
                case OrderExtPay::ORDER_PAY_TYPE_ON_LINE://线上支付
                    if ($this->orderExtPay->order_pay_money == 0) { //如果需要支付的金额等于0 则全部走余额支付
                        //交易记录
                        $order['payment_customer_trans_record_online_balance_pay'] = $this->orderExtPay->order_use_acc_balance;
                        $order['payment_source'] = 20;
                        if (Payment::balancePay($order)) {
                            $order_model->admin_id = $attributes['admin_id'];
                            OrderStatus::_payment($order_model, ['OrderExtPay']);
                        }
                    }
                    break;
                case OrderExtPay::ORDER_PAY_TYPE_POP://第三方预付
                    //交易记录
                    $order['payment_customer_trans_record_pre_pay'] = $this->orderExtPop->order_pop_order_money;
                    $order['payment_source'] = $this->channel_id;
                    if (Payment::prePay($order)) {
                        $order_model->admin_id = $attributes['admin_id'];
                        OrderStatus::_payment($order_model, ['OrderExtPay']);
                    }
                    break;
                default:
                    break;
            }
            return true;
        }
        return false;
    }

    /**
     * 周期订单
     *  @param $attributes [
     *  string $order_ip 下单IP地址 必填
     *  integer $order_service_type_id 服务类型 商品id 必填
     *  integer $order_src_id 订单来源id 必填
     *  string $channel_id 下单渠道 必填
     *  int $address_id 客户地址id 必填
     *  int $customer_id 客户id 必填
     *  string $order_customer_phone 客户手机号 必填
     *  int $admin_id 操作人id 0客户 1系统 必填
     *  int $order_pay_type 支付方式 1现金 2线上 3第三方 必填
     *  int $order_is_use_balance 是否使用余额 0否 1是 必填
     *  string $order_booked_worker_id 指定阿姨id
     *  string $order_customer_need 客户需求
     *  string $order_customer_memo 客户备注
     * ]
     * @param $booked_list [
     *      [
     *          int $order_booked_begin_time 预约开始时间 必填
     *          int $order_booked_end_time 预约结束时间 必填
     *          int $coupon_id 优惠券id
     *      ]
     * ]
     * @return bool
     */
    public function createNewBatch($attributes,$booked_list)
    {
        $transact = static::getDb()->beginTransaction();
        //如果指定阿姨则是周期订单分配周期订单号否则分配批量订单号
        $attributes['order_parent_id'] = 0;
        $attributes['order_is_parent'] = 0;
        if(isset($attributes['order_booked_worker_id']) && $attributes['order_booked_worker_id']>0)
        {
            $attributes['order_batch_code'] = OrderTool::createOrderCode('z');
        }else{
            $attributes['order_batch_code'] = OrderTool::createOrderCode('p');
        }
        foreach($booked_list as $booked){
            if(!$this->_create($attributes+$booked,$transact)){
                $transact->rollBack();
                return false;
            }
        }
        $transact->commit();
        return true;
    }


    /**
     * 在线支付完后调用
     * @param $order_id int 订单id
     * @param $admin_id int  后台管理员id 系统0 客户1
     * @param $pay_channel_id int  支付渠道id
     * @param $order_pay_channel_name string 支付渠道名称
     * @param $order_pay_flow_num string 支付流水号
     * @return bool
     */
    public static function isPaymentOnline($order_id,$admin_id,$pay_channel_id,$order_pay_channel_name,$order_pay_flow_num)
    {
        $order = OrderSearch::getOne($order_id);
        $order->setAttributes([
            'order_pay_type' => OrderExtPay::ORDER_PAY_TYPE_ON_LINE,
            'admin_id' => $admin_id,
            'pay_channel_id' => $pay_channel_id,
            'order_pay_channel_name' => $order_pay_channel_name,
            'order_pay_flow_num' => $order_pay_flow_num
        ]);
        return OrderStatus::_payment($order,['OrderExtPay']);
    }

    /**
     * 批量支付回调接口
     * @param $batch_code int 订单id
     * @param $admin_id int  后台管理员id 系统0 客户1
     * @param $pay_channel_id int  支付渠道id
     * @param $order_pay_channel_name string 支付渠道名称
     * @param $order_pay_flow_num string 支付流水号
     * @return bool
     */
    public static function isBatchPaymentOnline($batch_code,$admin_id,$pay_channel_id,$order_pay_channel_name,$order_pay_flow_num)
    {
        return OrderStatus::_batchPayment($batch_code,$admin_id,$pay_channel_id,$order_pay_channel_name,$order_pay_flow_num);
    }


    /**
     * 智能指派失败
     * @param $order_id
     * @return bool
     */
    public static function sysAssignUndone($order_id)
    {
        $order = OrderSearch::getOne($order_id);
        $order->admin_id = 1;
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
        return self::assignDone($order_id, $worker, 1, $assign_type);
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
        return self::assignDone($order_id, $worker, 1, $assign_type);
    }

    /**
     * 人工指派失败
     * @param $order_id
     * @param $admin_id
     * @return array|bool
     */
    public static function manualAssignUndone($order_id, $admin_id = 1)
    {
        $order = OrderSearch::getOne($order_id);
        $order->order_flag_lock = 0;
        $order->admin_id = $admin_id;
        if ($order->orderExtFlag->order_flag_send == 3) { //小家政和客服都无法指派出去
            OrderPool::remOrderForWorkerPushList($order->id,true); //永久从接单大厅中删除此订单
            return OrderStatus::_manualAssignUndone($order, ['OrderExtFlag']);
        } else {//客服或小家政还没指派过则进入待人工指派的状态
            OrderPool::reAddOrderToWorkerPushList($order_id); //重新添加到接单大厅
            return OrderStatus::_sysAssignUndone($order, ['OrderExtFlag']);
        }
    }

    /**
     * 批量解锁
     */
    public static function manualAssignUnlock()
    {
        $lockedOrders = OrderExtFlag::find()->where(['>', 'order_flag_lock', 0])->andWhere(['<', 'order_flag_lock_time', time() - Order::MANUAL_ASSIGN_lONG_TIME])->all();
        foreach ($lockedOrders as $v) {//解锁操作超时订单
            self::manualAssignUndone($v['order_id']);
        }
    }

    /**
     * 人工指派成功
     * @param $order_id
     * @param $worker_id
     * @param $admin_id
     *  @param bool $isCS
     * @return array
     */
    public static function manualAssignDone($order_id, $worker_id, $admin_id, $isCS = false)
    {
        $assign_type = $isCS ? OrderExtWorker::ASSIGN_TYPE_CS : OrderExtWorker::ASSIGN_TYPE_SHOP;
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
     * TODO 避免同一时间 给阿姨指派多个订单问题 需要处理
     * TODO 修改阿姨接单数量
     */
    public static function assignDone($order_id, $worker, $admin_id, $assign_type)
    {
        $result = false;
        $order = OrderSearch::getOne($order_id);
        if($order->orderExtFlag->order_flag_lock>0 && $order->orderExtFlag->order_flag_lock!=$admin_id && time()-$order->orderExtFlag->order_flag_lock_time<Order::MANUAL_ASSIGN_lONG_TIME){
            $order->addError('id','订单正在进行人工指派！');
        }elseif(OrderSearch::WorkerOrderExistsConflict($worker['id'],$order->order_booked_begin_time,$order->order_booked_end_time)>0){
            $order->addError('id','阿姨服务时间冲突！');
        }elseif($order->orderExtWorker->worker_id>0){
            $order->addError('id','订单已经指派阿姨！');
        }else {
            $order->order_flag_lock = 0;
            $order->worker_id = $worker['id'];
            $order->worker_type_id = $worker['worker_type'];
            $order->order_worker_phone = $worker['worker_phone'];
            $order->order_worker_name = $worker['worker_name'];
            $order->order_worker_type_name = $worker['worker_type_description'];
            $order->shop_id = $worker["shop_id"];
            $order->order_worker_shop_name = $worker["shop_name"];
            $order->order_worker_assign_type = $assign_type; //接单方式
            $order->admin_id = $admin_id;
            if ($admin_id > 1) { //大于1属于人工操作
                $result = OrderStatus::_manualAssignDone($order, ['OrderExtFlag', 'OrderExtWorker']);
            } elseif($order->orderExtStatus->order_status_dict_id==OrderStatusDict::ORDER_SYS_ASSIGN_START){ //当前状态如果是开始智能派单 就到智能派单成功 否则 到阿姨自助接单
                $result = OrderStatus::_sysAssignDone($order, ['OrderExtFlag', 'OrderExtWorker']);
            }else {
                $result = OrderStatus::_workerBindOrder($order, ['OrderExtFlag', 'OrderExtWorker']);
            }
            OrderPool::remOrderForWorkerPushList($order->id,true); //永久从接单大厅中删除此订单
        }
        return ['status'=>$result,'errors'=>$order->errors];
    }

    /**
     * 开始服务
     * @param $order_id
     * @return bool
     */
    public static function serviceStart($order_id)
    {
        $order = OrderSearch::getOne($order_id);
        return OrderStatus::_serviceStart($order);
    }

    /**
     * 服务完成
     * @param $order_id
     * @return bool
     */
    public static function serviceDone($order_id)
    {
        $order = OrderSearch::getOne($order_id);
        return OrderStatus::_serviceDone($order);
    }

    /**
     * 取消订单
     * @param $order_id
     * @param $admin_id
     * @param $memo
     * @param $cause 1公司原因 2个人原因
     * @return bool
     */
    public static function cancel($order_id, $admin_id, $cause,$memo = '')
    {
        $order = OrderSearch::getOne($order_id);
        $order->admin_id = $admin_id;
        $order->order_flag_cancel_cause = $cause;
        $current_status = $order->orderExtStatus->order_status_dict_id;
        if(in_array($current_status,[  //只有在以下状态下才可以取消订单
            OrderStatusDict::ORDER_INIT,
            OrderStatusDict::ORDER_WAIT_ASSIGN,
            OrderStatusDict::ORDER_SYS_ASSIGN_DONE,
            OrderStatusDict::ORDER_SYS_ASSIGN_UNDONE,
            OrderStatusDict::ORDER_MANUAL_ASSIGN_DONE,
            OrderStatusDict::ORDER_MANUAL_ASSIGN_UNDONE,
        ])) {
            OrderPool::remOrderForWorkerPushList($order->id, true); //永久从接单大厅中删除此订单
            if ($admin_id == 0) {
                $order->order_customer_memo = $memo;
                $result = OrderStatus::_cancel($order, ['OrderExtCustomer']);
            } elseif ($admin_id == 1) {
                $order->order_sys_memo = $memo;
                $result = OrderStatus::_cancel($order);
            } elseif ($admin_id == 2) {
                $order->order_worker_memo = $memo;
                $result = OrderStatus::_cancel($order, ['OrderExtWorker']);
            } elseif ($admin_id > 2) {
                $order->order_cs_memo = $memo;
                $result = OrderStatus::_cancel($order);
            }
            if($result && $order->orderExtPay->order_pay_type==OrderExtPay::ORDER_PAY_TYPE_ON_LINE && $current_status!=OrderStatusDict::ORDER_INIT){
                //TODO 调高峰的退款接口
            }
        }else{
            return false;
        }
    }

    /**
     * 客户删除订单
     * @param $order_id
     * @param $admin_id
     * @return bool
     */
    public static function customerDel($order_id, $admin_id = 0)
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
     * @return bool
     */
    private function _create($attributes,$transact=null)
    {
        $this->setAttributes($attributes);
        $status_from = OrderStatusDict::findOne(OrderStatusDict::ORDER_INIT); //创建订单状态
        $status_to = OrderStatusDict::findOne(OrderStatusDict::ORDER_INIT); //初始化订单状态
        $order_code = OrderTool::createOrderCode(); //创建订单号

        $customer = Customer::getCustomerById($this->customer_id);
        $this->setAttributes([
            'order_customer_phone' => $customer->customer_phone,
            'customer_is_vip' => $customer->customer_is_vip,
        ]);

        try {
            $address = CustomerAddress::getAddress($this->address_id);
        } catch (Exception $e) {
            $this->addError('order_address', '创建时获取地址异常！');
            return false;
        }
        try {
            $goods = self::getGoods($address['customer_address_longitude'], $address['customer_address_latitude'], $attributes['order_service_type_id']);
        } catch (Exception $e) {
            $this->addError('order_service_type_name', '创建时获商品信息异常！');
            return false;
        }
        if (empty($goods)) {
            $this->addError('order_service_type_name', '创建时获商品信息失败！');
            return false;
        }
        $this->setAttributes([
            'order_unit_money' => $goods['operation_shop_district_goods_price'], //单价
            'order_service_type_name' => $goods['operation_shop_district_goods_name'], //商品名称
            'order_booked_count' => floatval(($this->order_booked_end_time - $this->order_booked_begin_time) / 3600), //TODO 精品保洁另算时长
        ]);
        $this->setAttributes([
            'order_money' => $this->order_unit_money * $this->order_booked_count, //订单总价
            'city_id' =>$address['operation_city_id'],
            'district_id' => $goods['district_id'],
            'order_address' => $address['operation_province_name'] . ',' . $address['operation_city_name'] . ',' . $address['operation_area_name'] . ',' . $address['customer_address_detail'] . ',' . $address['customer_address_nickname'] . ',' . $address['customer_address_phone'], //地址信息
        ]);


        if ($this->order_pay_type == OrderExtPay::ORDER_PAY_TYPE_POP) { //第三方预付
            $this->order_pop_operation_money = $this->order_money - $this->order_pop_order_money; //渠道运营费
        } elseif ($this->order_pay_type == OrderExtPay::ORDER_PAY_TYPE_ON_LINE) {//线上支付
            $this->order_pay_money = $this->order_money; //支付金额
            if (!empty($this->coupon_id)) {//是否使用了优惠券
                $coupon = self::getCouponById($this->coupon_id);
                $this->order_use_coupon_money = $coupon['coupon_money'];
                $this->order_pay_money -= $this->order_use_coupon_money;
            }
            if ($this->order_is_use_balance == 1) {
                try {
                    $customer = Customer::getCustomerInfo($this->order_customer_phone);
                } catch (Exception $e) {
                    $this->addError('order_use_acc_balance', '创建时获客户余额信息失败！');
                    return false;
                }
                if ($customer['customer_balance'] < $this->order_pay_money) { //用户余额小于需支付金额
                    $this->order_use_acc_balance = $customer['customer_balance']; //使用余额为用户余额
                } else {
                    $this->order_use_acc_balance = $this->order_pay_money; //使用余额为需支付金额
                }
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

        return $this->doSave(['OrderExtCustomer','OrderExtFlag','OrderExtPay','OrderExtPop','OrderExtStatus','OrderExtWorker','OrderStatusHistory'],$transact);
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
        if (!empty($shop_district_info)) {
            $goods = OperationShopDistrictGoods::getShopDistrictGoodsList($shop_district_info['operation_city_id'], $shop_district_info['operation_shop_district_id']);
            if (!empty($goods)) {
                if ($goods_id == 0) {
                    return ['code' => 200, 'data' => $goods,'district_id'=>$shop_district_info['operation_shop_district_id']];
                } else {
                    foreach ($goods as $v) {
                        if ($v['operation_goods_id'] == $goods_id) {
                            $v['district_id'] = $shop_district_info['operation_shop_district_id'];
                            return $v;
                        }
                    }
                    return ['code' => 500, 'msg' => '获取商品信息失败：没有匹配的商品'];
                }
            } else {
                return ['code' => 501, 'msg' => '获取商品信息失败：没有匹配的商品'];
            }
        } else {
            return ['code' => 502, 'msg' => '获取商品信息失败：没有匹配的商圈'];
        }
    }

    /**
     * 获取优惠券
     * @param $id
     * @return mixed
     */
    public static function getCouponById($id)
    {
        $coupon = [
            1 => [
                "id" => 1,
                "coupon_name" => "优惠券30",
                "coupon_money" => 30
            ],
            2 => [
                "id" => 2,
                "coupon_name" => "优惠券30",
                "coupon_money" => 30
            ],
            3 => [
                "id" => 3,
                "coupon_name" => "优惠券30",
                "coupon_money" => 30
            ]
        ];
        return $coupon[$id];
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
     * @date: 2015-10-26
     * @author: peak pan
     * @return:
     **/
    
    public static function getDistrictList()
    {
    	$districtList = OperationShopDistrict::getCityShopDistrictList();
    	return $districtList?ArrayHelper::map($districtList,'id','operation_shop_district_name'):[];
    }
    
    
    /**
     * 核实用户订单唯一性
     * @param   $customer_id   int 用户id
     * @param   $order_id       int  订单id
     * @return  int
     */
    
    public static function validationOrderCustomer($customer_id, $order_id)
    {
        return OrderExtCustomer::find()->where(["customer_id" => $customer_id, "order_id" => $order_id])->count();
    }


    /*
     * 获取订单状态列表
     */
    public static function getStatusList()
    {
        $statusList = OrderStatusDict::find()->asArray()->all();
        return $statusList ? ArrayHelper::map($statusList, 'id', 'order_status_name') : [];
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
        return date('H:i', $this->order_booked_begin_time).'-'.date('H:i', $this->order_booked_end_time);
    }
}
