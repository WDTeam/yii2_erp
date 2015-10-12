<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/10/10
 * Time: 13:39
 */
namespace core\models\order;


use Yii;
use yii\base\Model;

class OrderPay extends Model
{
    /**
     * 现金支付
     * @param $order_id int
     * @param $admin_id int
     * @return bool
     */
    public static function isPaymentOffLine($order_id,$admin_id)
    {
        $order = Order::findOne($order_id);
        $order->setAttributes([
            'admin_id' => $admin_id,
            'order_pay_type' => Order::ORDER_PAY_TYPE_OFF_LINE
        ]);
        return OrderStatus::payment($order,['OrderExtPay']);
    }


    /**
     * 第三方预付
     * @param $order_id
     * @param $admin_id
     * @param $channel_id
     * @param $order_pop_group_buy_code
     * @param $order_pop_order_code
     * @param $order_pop_order_money
     * @param $order_pop_operation_money
     * @return bool
     */
    public static function isPaymentPop($order_id,$admin_id,$channel_id,$order_pop_group_buy_code,$order_pop_order_code,$order_pop_order_money,$order_pop_operation_money)
    {
        $order = Order::findOne($order_id);
        $order->setAttributes([
            'order_pay_type' => Order::ORDER_PAY_TYPE_POP,
            'admin_id'=>$admin_id,
            'channel_id'=>$channel_id,
            'order_pop_group_buy_code'=>$order_pop_group_buy_code,
            'order_pop_order_code'=>$order_pop_order_code,
            'order_pop_order_money'=> $order_pop_order_money,
            'order_pop_operation_money'=>$order_pop_operation_money
        ]);
        return OrderStatus::payment($order,['OrderExtPay','OrderExtPop']);
    }

    /**
     * 线上支付
     * @param $order_id int 订单id
     * @param $admin_id int  后台管理员id 系统0 客户1
     * @param $pay_channel_id int  支付渠道id
     * @param $order_pay_channel_name string 支付渠道名称
     * @param $order_pay_flow_num string 支付流水号
     * @param $order_pay_money float 支付金额
     * @param $order_use_acc_balance float 使用余额
     * @param $card_id int 服务卡ID
     * @param $order_use_card_money float 使用服务卡金额
     * @param $coupon_id int 优惠券ID
     * @param $order_use_coupon_money float 使用优惠卷金额
     * @param $promotion_id int 促销id
     * @param $order_use_promotion_money float 使用促销金额
     * @return bool
     */
    public static function isPaymentOnline($order_id,$admin_id,$pay_channel_id,$order_pay_channel_name,$order_pay_flow_num,$order_pay_money,$order_use_acc_balance,$card_id,$order_use_card_money,$coupon_id,$order_use_coupon_money,$promotion_id,$order_use_promotion_money)
    {
        $order = Order::findOne($order_id);
        $order->setAttributes([
            'order_pay_type' => Order::ORDER_PAY_TYPE_ON_LINE,
            'admin_id' => $admin_id,
            'pay_channel_id' => $pay_channel_id,
            'order_pay_channel_name' => $order_pay_channel_name,
            'order_pay_flow_num' => $order_pay_flow_num,
            'order_pay_money' => $order_pay_money,
            'order_use_acc_balance' => $order_use_acc_balance,
            'card_id' => $card_id,
            'order_use_card_money' => $order_use_card_money,
            'coupon_id' => $coupon_id,
            'order_use_coupon_money' => $order_use_coupon_money,
            'promotion_id' => $promotion_id,
            'order_use_promotion_money' => $order_use_promotion_money,
        ]);
        return OrderStatus::payment($order,['OrderExtPay']);
    }
}