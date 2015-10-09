<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/9/23
 * Time: 18:30
 */

namespace core\models\order;

use common\models\OrderExtCustomer;
use common\models\OrderExtFlag;
use common\models\OrderExtPay;
use common\models\OrderExtStatus;
use common\models\OrderExtPop;
use common\models\OrderExtWorker;
use Yii;
use common\models\Order as OrderModel;
use common\models\OrderHistory;
use common\models\OrderStatusHistory;
use common\models\OrderStatusDict;
use common\models\OrderSrc;

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
     * @param $attributes
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
     * 现金支付
     * @param $order Order
     * @return bool
     */
    public static function isPaymentOffLine($order)
    {
        $from = OrderStatusDict::findOne($order->orderExtStatus->order_status_dict_id); //当前订单状态
        $to = OrderStatusDict::findOne(OrderStatusDict::ORDER_IS_PAY); //变更为已支付带指派状态
        $order->setAttributes([
            'order_pay_type' => self::ORDER_PAY_TYPE_OFF_LINE
        ]);
        return self::statusChange($order,$from,$to,['OrderExtPay']);
    }




    /**
     * 修改订单状态
     * @param $order Order
     * @param $from OrderStatusDict
     * @param $to OrderStatusDict
     * @param $must_models array
     * @return bool
     */
    public static function statusChange($order, $from, $to, $must_models=[])
    {
        $order->setAttributes([
            'order_before_status_dict_id' => $from->id,
            'order_before_status_name' => $from->order_status_name,
            'order_status_dict_id' => $to->id,
            'order_status_name' => $to->order_status_name
        ]);
        $save_models = ['OrderExtStatus','OrderStatusHistory'];
        $save_models = array_merge($must_models,$save_models);
        return $order->doSave($save_models);
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
        $channel = [1 => 'BOSS', 2 => '美团', 3 => '大众点评'];
        return $channel_id == 0 ? $channel : (isset($channel[$channel_id]) ? $channel[$channel_id] : false);
    }

    /**
     * 获取服务
     * @param int $service_id
     * @return array|bool
     */
    public function getServiceList($service_id = 0)
    {
        $service = [1 => '家庭保洁', 2 => '新居开荒', 3 => '擦玻璃'];
        return $service_id == 0 ? $service : (isset($service[$service_id]) ? $service[$service_id] : false);
    }

    /**
     * 获取优惠券
     * @param $id
     * @return mixed
     */
    public function getCouponById($id)
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
     * 创建订单
     * @param $attributes
     * @return bool
     */
    private function _create($attributes)
    {
        $this->setAttributes($attributes);
        $statusFrom = OrderStatusDict::findOne(OrderStatusDict::ORDER_INIT); //创建订单状态
        $statusTo = OrderStatusDict::findOne(OrderStatusDict::ORDER_INIT); //初始化订单状态
        $orderCode = date('ymdHis') . str_pad($this->order_service_type_id, 2, '0', STR_PAD_LEFT) . str_pad($this->customer_id, 10, '0', STR_PAD_LEFT); //TODO 订单号待优化
        $this->setAttributes([
            //创建订单时优惠卷和服务卡都是初始状态
            'coupon_id' => 0,
            'order_use_coupon_money' => 0,
            'card_id' => 0,
            'order_use_card_money' => 0,
            //为以下数据赋初始值
            'order_code' => $orderCode,
            'order_before_status_dict_id' => $statusFrom->id,
            'order_before_status_name' => $statusFrom->order_status_name,
            'order_status_dict_id' => $statusTo->id,
            'order_status_name' => $statusTo->order_status_name,
            'order_src_name' => $this->getOrderSrcName($this->order_src_id),
            'order_channel_name' => $this->getOrderChannelList($this->channel_id),
            'order_service_type_name' => $this->getServiceList($this->order_service_type_id),
            'order_flag_send' => 0, //'指派不了 0可指派 1客服指派不了 2小家政指派不了 3都指派不了',
            'order_flag_urgent' => 0,//加急 数字越大约紧急
            'order_flag_exception' => 0,//异常标识
            'order_flag_lock' => 0,
            'worker_id' => 0,
            'worker_type_id' => 0,
            'order_worker_send_type' => 0,
            'comment_id' => 0,
            'order_customer_hidden' => 0,
            'order_pop_pay_money' => 0,
            'shop_id' => 0,
            'order_worker_type_name' => '',
            'pay_channel_id' => 0,//支付渠道id
            'order_pay_channel_name' => '',//支付渠道
            'order_pay_flow_num' => '',//支付流水号
            'order_pay_money' => 0,//支付金额
            'invoice_id' => 0, //发票id 用户需求中有开发票就绑定发票id
            'checking_id' => 0,
            'isdel' => 0,
        ]);

        if ($this->doSave()) {
            return true;
        }
        return false;
    }
}