<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/10/10
 * Time: 13:39
 */
namespace core\models\order;


use Yii;
use yii\base\Exception;
use common\models\order\OrderStatusDict;
use common\models\order\OrderStatusHistory;

class OrderStatus extends Order
{


    /**
     * 变更为已支付待指派状态
     * @param $order
     * @param $must_models
     * @return bool
     */
    protected static function _payment(&$order,$must_models=[]){
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_WAIT_ASSIGN); //变更为已支付待指派状态
        if(self::_statusChange($order,$status,$must_models)){
            //支付成功后如果需要系统派单则把订单放入订单池
            if($order->orderExtFlag->order_flag_sys_assign==1) {
                // 开始系统指派
                if (self::_sysAssignStart($order->id)) {
                    OrderPool::addOrder($order->id);
                }
            }
            return true;
        }
        return false;
    }

    /**
     * 开始智能指派
     * @param $order_id
     * @return bool
     */
    protected static function _sysAssignStart($order_id)
    {
        $order = OrderSearch::getOne($order_id);
        $order->admin_id = 1;
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_SYS_ASSIGN_START);
        return self::_statusChange($order,$status);
    }

    /**
     * 完成智能指派
     * @param $order
     * @param $must_models
     * @return bool
     */
    protected static function _sysAssignDone(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_SYS_ASSIGN_DONE);
        return self::_statusChange($order,$status,$must_models);
    }

    /**
     * 智能指派失败
     * @param $order
     * @param $must_models
     * @return bool
     */
    protected static function _sysAssignUndone(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_SYS_ASSIGN_UNDONE);
        return self::_statusChange($order,$status,$must_models);
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
        return self::_statusChange($order,$status,$must_models);
    }

    /**
     * 完成人工指派
     * @param $order
     * @param $must_models
     * @return bool
     */
    protected static function _manualAssignDone(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_MANUAL_ASSIGN_DONE);
        return self::_statusChange($order,$status,$must_models);
    }

    /**
     * 人工指派失败
     * @param $order
     * @param $must_models
     * @return bool
     */
    protected static function _manualAssignUndone(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_MANUAL_ASSIGN_UNDONE);
        return self::_statusChange($order,$status,$must_models);
    }

    /**
     * 开始服务
     * @param $order
     * @param $must_models
     * @return bool
     */
    protected static function _serviceStart(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_SERVICE_START);
        return self::_statusChange($order,$status,$must_models);
    }

    /**
     * 完成服务
     * @param $order
     * @param $must_models
     * @return bool
     */
    protected static function _serviceDone(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_SERVICE_DONE);
        return self::_statusChange($order,$status,$must_models);
    }

    /**
     * 完成评价 用户确认
     * @param $order
     * @param $must_models
     * @return bool
     */
    protected static function _customerAcceptDone(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_CUSTOMER_ACCEPT_DONE);
        return self::_statusChange($order,$status,$must_models);
    }


    /**
     * 已核实 已对账
     * @param $order
     * @param $must_models
     * @return bool
     */
    protected static function _checked(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_CHECKED);
        return self::_statusChange($order,$status,$must_models);
    }

    /**
     * 已完成结算
     * @param $order
     * @param $must_models
     * @return bool
     */
    protected static function _payoffDone(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_PAYOFF_DONE);
        return self::_statusChange($order,$status,$must_models);
    }

    /**
     * 已完成门店结算
     * @param $order
     * @param $must_models
     * @return bool
     */
    protected static function _payoffShopDone(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_PAYOFF_SHOP_DONE);
        return self::_statusChange($order,$status,$must_models);
    }


    /**
     * 取消订单
     * @param $order
     * @param $must_models
     * @return bool
     */
    protected static function _cancel(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_CANCEL);
        return self::_statusChange($order,$status,$must_models);
    }

    /**
     * 已归档
     * @param $order
     * @param $must_models
     * @return bool
     */
    protected static function Died(&$order,$must_models=[])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_DIED);
        return self::_statusChange($order,$status,$must_models);
    }






    /**
     * 修改订单状态
     * @param $order Order
     * @param $status OrderStatusDict
     * @param $must_models array
     * @return bool
     */
    private static function _statusChange(&$order, $status, $must_models=[])
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

    /**
     * 查询订单状态历史
     */
    public static function searchOrderStatusHistory($order_id){
        return OrderStatusHistory::find()->where(["order_id"=>$order_id])->orderBy(["created_at"=>SORT_DESC])->all();
    }
}