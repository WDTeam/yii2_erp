<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/10/10
 * Time: 13:39
 */
namespace core\models\order;


use Yii;
use yii\base\Event;
use yii\base\Exception;
use yii\base\Model;
use common\models\OrderStatusDict;

class OrderStatus extends Model
{

    /**
     * 在线支付完后调用修改订单状态
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
            'order_pay_type' => Order::ORDER_PAY_TYPE_ON_LINE,
            'admin_id' => $admin_id,
            'pay_channel_id' => $pay_channel_id,
            'order_pay_channel_name' => $order_pay_channel_name,
            'order_pay_flow_num' => $order_pay_flow_num
        ]);
        return self::payment($order,['OrderExtPay']);
    }

    /**
     * 变更为已支付待指派状态
     * @param $order
     * @param $must_models
     * @return bool
     */
    public static function payment(&$order,$must_models=[]){
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_WAIT_ASSIGN); //变更为已支付待指派状态
        if(self::statusChange($order,$status,$must_models)){
            //支付成功后如果需要系统派单则把订单放入订单池
            if($order->orderExtFlag->order_flag_sys_assign==1) {
                Order::addOrderToPool($order->id);
            }
            return true;
        }
        return false;
    }

    /**
     * 开始智能指派
     * @param $order
     * @param $must_models
     * @return bool
     */
    public static function sysAssignStart(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_SYS_ASSIGN_START);
        return self::statusChange($order,$status,$must_models);
    }

    /**
     * 完成智能指派
     * @param $order
     * @param $must_models
     * @return bool
     */
    public static function sysAssignDone(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_SYS_ASSIGN_DONE);
        return self::statusChange($order,$status,$must_models);
    }

    /**
     * 智能指派失败
     * @param $order
     * @param $must_models
     * @return bool
     */
    public static function sysAssignUndone(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_SYS_ASSIGN_UNDONE);
        return self::statusChange($order,$status,$must_models);
    }

    /**
     * 开始人工指派
     * @param $order
     * @param $must_models
     * @return bool
     */
    public static function manualAssignStart(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_MANUAL_ASSIGN_START);
        return self::statusChange($order,$status,$must_models);
    }

    /**
     * 完成人工指派
     * @param $order
     * @param $must_models
     * @return bool
     */
    public static function manualAssignDone(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_MANUAL_ASSIGN_DONE);
        return self::statusChange($order,$status,$must_models);
    }

    /**
     * 人工指派失败
     * @param $order
     * @param $must_models
     * @return bool
     */
    public static function manualAssignUndone(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_MANUAL_ASSIGN_UNDONE);
        return self::statusChange($order,$status,$must_models);
    }

    /**
     * 开始服务
     * @param $order
     * @param $must_models
     * @return bool
     */
    public static function serviceStart(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_SERVICE_START);
        return self::statusChange($order,$status,$must_models);
    }

    /**
     * 完成服务
     * @param $order
     * @param $must_models
     * @return bool
     */
    public static function serviceDone(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_SERVICE_DONE);
        return self::statusChange($order,$status,$must_models);
    }

    /**
     * 完成评价 用户确认
     * @param $order
     * @param $must_models
     * @return bool
     */
    public static function customerAcceptDone(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_CUSTOMER_ACCEPT_DONE);
        return self::statusChange($order,$status,$must_models);
    }

    /**
     * 申请结算
     * @param $order
     * @param $must_models
     * @return bool
     */
    public static function applyWagesDone(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_APPLY_WAGES_DONE);
        return self::statusChange($order,$status,$must_models);
    }

    /**
     * 已核实 已对账
     * @param $order
     * @param $must_models
     * @return bool
     */
    public static function checked(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_CHECKED);
        return self::statusChange($order,$status,$must_models);
    }

    /**
     * 已完成结算
     * @param $order
     * @param $must_models
     * @return bool
     */
    public static function payoffDone(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_PAYOFF_DONE);
        return self::statusChange($order,$status,$must_models);
    }

    /**
     * 已完成门店结算
     * @param $order
     * @param $must_models
     * @return bool
     */
    public static function payoffShopDone(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_PAYOFF_SHOP_DONE);
        return self::statusChange($order,$status,$must_models);
    }


    /**
     * 取消订单
     * @param $order
     * @param $must_models
     * @return bool
     */
    public static function cancel(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_CANCEL);
        return self::statusChange($order,$status,$must_models);
    }

    /**
     * 已归档
     * @param $order
     * @param $must_models
     * @return bool
     */
    public static function Died(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_DIED);
        return self::statusChange($order,$status,$must_models);
    }






    /**
     * 修改订单状态
     * @param $order Order
     * @param $status OrderStatusDict
     * @param $must_models array
     * @return bool
     */
    public static function statusChange(&$order, $status, $must_models=[])
    {
        try {
            $from = OrderStatusDict::findOne($order->orderExtStatus->order_status_dict_id); //当前订单状态
            $order->setAttributes([
                'order_before_status_dict_id' => $from->id,
                'order_before_status_name' => $from->order_status_name,
                'order_status_dict_id' => $status->id,
                'order_status_name' => $status->order_status_name
            ]);
            $save_models = ['OrderExtStatus', 'OrderStatusHistory'];
            $save_models = array_merge($must_models, $save_models);
            return $order->doSave($save_models);
        }catch (Exception $e){
            return false;
        }
    }
}