<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/9/23
 * Time: 18:30
 */

namespace core\models\order;


use core\models\Customer;
use core\models\GeneralPay\GeneralPay;
use core\models\worker\Worker;
use Yii;
use Redis;
use common\models\Order as OrderModel;
use common\models\OrderStatusDict;
use common\models\OrderSrc;
use common\models\FinanceOrderChannel;
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
 * @property integer $order_ip
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
 * @property string $card_id
 * @property string $order_use_card_money
 * @property string $coupon_id
 * @property string $order_use_coupon_money
 * @property string $promotion_id
 * @property string $order_use_promotion_money
 * @property string $worker_id
 * @property string $worker_type_id
 * @property string $order_worker_type_name
 * @property integer $order_worker_assign_type
 * @property string $shop_id
 * @property string $checking_id
 * @property string $order_cs_memo
 * @property string $admin_id
 */
class Order extends OrderModel
{

    /**
     * 创建新订单
     * @param $attributes [
     *  integer $order_ip 下单IP地址 必填
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
     *  int $order_is_use_balance 是否使用余额 0否 1是
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
        return $this->_create($attributes);
    }

    /**
     * 追加新订单
     * @param $attributes
     * @return bool
     * TODO 追加订单默认是否使用主订单阿姨？
     */
    public function addNew($attributes)
    {
        if ($attributes['order_parent_id'] <= 0) {
            $this->addError('order_parent_id', '追加订单必须指定主订单ID！');
        } else {
            $attributes['order_is_parent'] = 1;
            return $this->_create($attributes);
        }
    }


    /**
     * 把订单放入订单池
     * @param $order_id
     */
    public static function addOrderToPool($order_id)
    {
        $order = Order::findById($order_id);
       //放入订单池 zset 根据预约开始时间+订单id排序
//        $redis = new Redis();
//        $redis->zAdd('WaitAssignOrdersPool',$order->order_booked_begin_time.$order_id,$order);

        //TODO 开始系统指派
        $order->admin_id=0;
        if(OrderStatus::sysAssignStart($order,[]))
        {
            //TODO 系统指派失败
            $order = Order::findOne($order_id);
            $order->admin_id=0;
            OrderStatus::sysAssignUndone($order,[]);
        }
    }


    /**
     * 人工指派失败
     * @param $order_id
     * @param $admin_id
     * @param bool $isCS
     * @return array|bool
     */
    public static function manualAssignUndone($order_id,$admin_id,$isCS = false)
    {
        $flag_send = $isCS?1:2;
        $order = Order::findOne($order_id);
        if($order->orderExtFlag->order_flag_send+$flag_send==3) //小家政和客服都无法指派出去
        {
            $order->order_flag_send = $order->orderExtFlag->order_flag_send+$flag_send; //标记是谁指派不了
            $order->order_flag_lock = 0;
            $order->admin_id = $admin_id;
            if(OrderStatus::manualAssignUndone($order,['OrderExtFlag'])){
                return true;
            }
        }else{//客服或小家政还没指派过则进入待人工指派的状态
            $order->order_flag_send = $order->orderExtFlag->order_flag_send+$flag_send;
            $order->order_flag_lock = 0;
            $order->admin_id = $admin_id;
            if(OrderStatus::sysAssignUndone($order,['OrderExtFlag'])){
                return true;
            }
        }
        return false;
    }

    /**
     * 人工指派成功
     * @param $order_id
     * @param $worker_id
     * @param $admin_id
     *  @param bool $isCS
     * @return bool
     * TODO 避免同一时间 给阿姨指派多个订单问题 需要处理
     */
    public static function manualAssignDone($order_id,$worker_id,$admin_id,$isCS = false)
    {
        $order = Order::findOne($order_id);
        $order->order_flag_lock = 0;
        $order->worker_id = $worker_id;
        $worker = Worker::getWorkerInfo($worker_id);
        $order->worker_type_id = $worker['worker_type'];
        $order->order_worker_type_name =  $worker['worker_type_description'];
        $order->shop_id = $worker["shop_id"];
        $order->order_worker_assign_type = $isCS?2:3; //2客服指派 3门店指派
        $order->admin_id = $admin_id;
        return OrderStatus::manualAssignDone($order,['OrderExtFlag','OrderExtWorker']);
    }

    /**
     * 创建订单
     * @param $attributes
     * @return bool
     */
    private function _create($attributes)
    {
        $this->setAttributes($attributes);
        $status_from = OrderStatusDict::findOne(OrderStatusDict::ORDER_INIT); //创建订单状态
        $status_to = OrderStatusDict::findOne(OrderStatusDict::ORDER_INIT); //初始化订单状态
        $order_count = OrderSearch::getCustomerOrderCount($this->customer_id); //该用户的订单数量
        $order_code = strlen($this->customer_id).$this->customer_id.strlen($order_count).$order_count ; //TODO 订单号待优化

        $address = Customer::getCustomerAddresses($this->address_id);
        $goods = self::getGoods($address['customer_address_longitude'],$address['customer_address_latitude'],$attributes['order_service_type_id']);
        $this->setAttributes([
            'order_unit_money'=> $goods['operation_shop_district_goods_price'], //单价
            'order_service_type_name'=> $goods['operation_shop_district_goods_name'], //商品名称
            'order_booked_count' => ($this->order_booked_end_time-$this->order_booked_begin_time)/60, //时长
        ]);
        $this->setAttributes([
            'order_money'=> $this->order_unit_money*$this->order_booked_count/60, //订单总价
            'order_address'=>$address['customer_address_detail'].','.$address['customer_address_nickname'].','.$address['customer_address_phone'],//地址信息
        ]);


        if($this->$order_pay_type==3){ //第三方预付
            $this->order_pop_operation_money=$this->order_money-$this->order_pop_order_money; //渠道运营费
        }elseif($this->$order_pay_type==2){//线上支付
            $this->order_pay_money = $this->order_money;//支付金额
            if(!empty($this->coupon_id)){//是否使用了优惠券
                $this->order_use_coupon_money = self::getCouponById($this->coupon_id);
                $this->order_pay_money -= $this->order_use_coupon_money;
            }
            if($this->order_is_use_balance==1){
                $customer = Customer::getCustomerInfo($this->order_customer_phone);
                if($customer['customer_balance']<$this->order_pay_money){ //用户余额小于需支付金额
                    $this->order_use_acc_balance = $customer['customer_balance']; //使用余额为用户余额
                }else{
                    $this->order_use_acc_balance = $this->order_pay_money; //使用余额为需支付金额
                }
                $this->order_pay_money -= $this->order_use_coupon_money;
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
            'order_flag_urgent' => 0,//加急 数字越大约紧急
            'order_flag_exception' => 0,//异常标识
            'order_flag_lock' => 0,
            'worker_id' => 0,
            'worker_type_id' => 0,
            'order_worker_send_type' => 0,
            'comment_id' => 0,
            'order_customer_hidden' => 0,
            'order_pop_pay_money' => 0, //第三方结算金额
            'shop_id' => 0,
            'order_worker_type_name' => '',
            'pay_channel_id' => 0,//支付渠道id
            'order_pay_channel_name' => '',//支付渠道
            'order_pay_flow_num' => '',//支付流水号
            'invoice_id' => 0, //发票id 用户需求中有开发票就绑定发票id
            'checking_id' => 0,
            'isdel' => 0,
        ]);

        if ($this->doSave()) {
            //订单创建成功绑定添加到订单池的事件 支付成功后触发
            Yii::$app->on('addOrderToPool', function ($event) {
                Order::addOrderToPool($event->sender->id);
            });
            $order = $this->attributes;
            switch($this->orderExtPay->order_pay_type){
                case self::ORDER_PAY_TYPE_OFF_LINE://现金支付
                    //交易记录
                    $order['customer_trans_record_cash'] = $this->order_money;
                    $order['general_pay_source'] = 20;
                    if(GeneralPay::cashPay($order)){
                        $this->admin_id = $attributes['admin_id'];
                        OrderStatus::payment($this,['OrderExtPay']);
                    }
                    break;
                case self::ORDER_PAY_TYPE_ON_LINE://线上支付
                    if($this->orderExtPay->order_pay_money==0){ //如果需要支付的金额等于0 则全部走余额支付
                        //交易记录
                        $order['customer_trans_record_online_balance_pay'] = $this->orderExtPay->order_use_acc_balance;
                        $order['general_pay_source'] = 20;
                        if(GeneralPay::balancePay($order)){
                            $this->admin_id = $attributes['admin_id'];
                            OrderStatus::payment($this,['OrderExtPay']);
                        }
                    }
                    break;
                case self::ORDER_PAY_TYPE_POP://第三方预付
                    //交易记录
                    $order['customer_trans_record_pre_pay'] = $this->orderExtPop->order_pop_order_money;
                    $order['general_pay_source'] = $this->channel_id;
                    if(GeneralPay::perPay($order)){
                        $this->admin_id = $attributes['admin_id'];
                        OrderStatus::payment($this,['OrderExtPay']);
                    }
                    break;
                default:break;

            }
            return true;
        }
        return false;
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
        $channel = ArrayHelper::map($list,'id','finance_order_channel_name');
        return $channel_id == 0 ? $channel : (isset($channel[$channel_id]) ? $channel[$channel_id] : false);
    }


    public static function getGoods($longitude,$latitude,$goods_id=0)
    {
        $shop_district_info= OperationShopDistrictController::getCoordinateShopDistrict($longitude, $latitude);
        if(isset($shop_district_info['status']) && $shop_district_info['status']==1){
            $goods = OperationGoodsController::getGoodsList($shop_district_info['data']['operation_city_id'], $shop_district_info['data']['operation_shop_district_id']);
            if(isset($goods['status'])&&$goods['status']==1){
                if($goods_id==0){
                    return ['code'=>200,'data'=>$goods['data']];
                }else{
                    foreach($goods['data'] as $v){
                        if($v['operation_goods_id']==$goods_id){
                            return ['code'=>200,'data'=>$v];
                        }
                    }
                    return ['code'=>500,'msg'=>'获取商品信息失败：没有匹配的商品'];
                }
            }else{
                return ['code'=>501,'msg'=>'获取商品信息失败：没有匹配的商品'];
            }
        }else{
            return ['code'=>502,'msg'=>'获取商品信息失败：没有匹配的商圈'];
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
}