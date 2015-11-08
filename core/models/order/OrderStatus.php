<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/10/10
 * Time: 13:39
 */
namespace core\models\order;


use Yii;
use dbbase\models\order\OrderStatusHistory;

class OrderStatus extends Order
{


    /**
     * 变更为已支付待指派状态
     * @param $order
     * @param $must_models
     * @param $transact
     * @return bool
     */
    protected static function _payment(&$order, $must_models = [],$transact = null)
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_WAIT_ASSIGN); //变更为已支付待指派状态
        $current_status = $order->orderExtStatus->order_status_dict_id;
        if (in_array($current_status, [  //只有在以下状态下才可以改成待指派的状态
            OrderStatusDict::ORDER_INIT,//初始化
            OrderStatusDict::ORDER_MANUAL_ASSIGN_START, //人工指派中
        ])) {
            if (self::_statusChange($order, $status, $must_models, $transact)) {
                //支付成功后如果需要系统派单则把订单放入订单池
                if ($order->orderExtFlag->order_flag_sys_assign == 1) {
                    // 开始系统指派
                    if (self::_sysAssignStart($order->id)) {
                        OrderPool::addOrder($order->id);
                    }
                }
                OrderMsg::payment($order);
                return true;
            }
        }
        return false;
    }


    /**
     * 批量支付接口
     * @param $batch_code
     * @param $admin_id
     * @param $pay_channel_id
     * @param $order_pay_channel_name
     * @param $order_pay_flow_num
     * @return bool
     * @throws \yii\db\Exception
     */
    protected static function _batchPayment($batch_code, $admin_id, $pay_channel_id = 0, $order_pay_channel_name = '', $order_pay_flow_num = '')
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_WAIT_ASSIGN); //变更为已支付待指派状态
        $transact = static::getDb()->beginTransaction();
        $orders = OrderSearch::getBatchOrder($batch_code);
        foreach ($orders as $order) {
            $order->setAttributes([
                'admin_id' => $admin_id,
            ]);
            if ($pay_channel_id > 0) {
                $order->setAttributes([
                    'pay_channel_id' => $pay_channel_id,
                    'order_pay_channel_name' => $order_pay_channel_name,
                    'order_pay_flow_num' => $order_pay_flow_num
                ]);
            }
            if (!self::_statusChange($order, $status, ['OrderExtPay'], $transact)) {
                $transact->rollBack();
                return false;
            }
        }
        $transact->commit();
        if (substr($batch_code, 0, 1) == 'P') {
            foreach ($orders as $order) {
                //支付成功后如果需要系统派单则把订单放入订单池
                if ($order->orderExtFlag->order_flag_sys_assign == 1) {
                    // 开始系统指派
                    if (self::_sysAssignStart($order->id)) {
                        OrderPool::addOrder($order->id);
                    }

                }

            }
        }

        return true;
    }

    /**
     * 批量开始智能指派
     * @param $batch_code
     * @return bool
     */
    protected static function _batchSysAssignStart($batch_code)
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_SYS_ASSIGN_START);
        $transact = static::getDb()->beginTransaction();
        $orders = OrderSearch::getBatchOrder($batch_code);
        foreach ($orders as $order) {
            $order->admin_id = 1;
            if (!self::_statusChange($order, $status, [], $transact)) {
                $transact->rollBack();
                return false;
            }
        }
        $transact->commit();
        return true;
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
        return self::_statusChange($order, $status);
    }

    /**
     * 完成智能指派
     * @param $order
     * @param $must_models
     * @param $transact
     * @return bool
     */
    protected static function _sysAssignDone(&$order, $must_models = [], $transact = null)
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_SYS_ASSIGN_DONE);
        return self::_statusChange($order, $status, $must_models, $transact);
    }

    /**
     * 智能指派失败
     * @param $order
     * @param $must_models
     * @return bool
     */
    protected static function _sysAssignUndone(&$order, $must_models = [])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_SYS_ASSIGN_UNDONE);
        return self::_statusChange($order, $status, $must_models);
    }

    /**
     * 开始人工指派
     * @param $order
     * @param $must_models
     * @return bool
     */
    public static function manualAssignStart(&$order, $must_models = [])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_MANUAL_ASSIGN_START);
        return self::_statusChange($order, $status, $must_models);
    }

    /**
     * 批量开始人工指派
     * @param $batch_code
     * @param $admin_id
     * @param $is_cs
     * @return bool
     */
    public static function batchManualAssignStart($batch_code, $admin_id, $is_cs)
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_MANUAL_ASSIGN_START);
        $transact = static::getDb()->beginTransaction();
        $orders = OrderSearch::getBatchOrder($batch_code);
        foreach ($orders as $order) {
            $order->order_flag_lock = $admin_id;
            $order->order_flag_lock_time = time(); //加锁时间
            $order->order_flag_send = $order->orderExtFlag->order_flag_send + ($is_cs ? 1 : 2); //指派时先标记是谁指派不了
            $order->admin_id = $admin_id;
            if (!self::_statusChange($order, $status, ['OrderExtFlag'], $transact)) {
                $transact->rollBack();
                return false;
            }
        }
        $transact->commit();
        return true;
    }

    /**
     * 完成人工指派
     * @param $order
     * @param $must_models
     * @param $transact
     * @return bool
     */
    protected static function _manualAssignDone(&$order, $must_models = [], $transact = null)
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_MANUAL_ASSIGN_DONE);
        return self::_statusChange($order, $status, $must_models, $transact);
    }

    /**
     * 人工指派失败
     * @param $order
     * @param $must_models
     * @param $transact
     * @return bool
     */
    protected static function _manualAssignUndone(&$order, $must_models = [], $transact = null)
    {
        $current_status = $order->orderExtStatus->order_status_dict_id;
        if (in_array($current_status, [  //只有在以下状态下才可以转成人工指派失败
            OrderStatusDict::ORDER_MANUAL_ASSIGN_START, //人工指派开始
        ])) {
            $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_MANUAL_ASSIGN_UNDONE);
            return self::_statusChange($order, $status, $must_models, $transact);
        }
        return false;
    }

    /**
     * 阿姨自助接单
     * @param $order
     * @param $must_models
     * @param $transact
     * @return bool
     */
    protected static function _workerBindOrder(&$order, $must_models = [], $transact = null)
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_WORKER_BIND_ORDER);
        return self::_statusChange($order, $status, $must_models, $transact);
    }

    /**
     * 开始服务
     * @param $order
     * @param $must_models
     * @return bool
     */
    protected static function _serviceStart(&$order, $must_models = [])
    {
        $current_status = $order->orderExtStatus->order_status_dict_id;
        if (in_array($current_status, [  //只有在以下状态下才可以开始服务
            OrderStatusDict::ORDER_SYS_ASSIGN_DONE, //智能指派完成
            OrderStatusDict::ORDER_MANUAL_ASSIGN_DONE, //人工指派完成
            OrderStatusDict::ORDER_WORKER_BIND_ORDER, //阿姨抢单完成
        ])) {
            $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_SERVICE_START);
            return self::_statusChange($order, $status, $must_models);
        } else {
            return false;
        }
    }

    /**
     * 完成服务
     * @param $order
     * @param $must_models
     * @return bool
     */
    protected static function _serviceDone(&$order, $must_models = [])
    {
        if ($order->orderExtStatus->order_status_dict_id == OrderStatusDict::ORDER_SERVICE_START) {
            $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_SERVICE_DONE);
            return self::_statusChange($order, $status, $must_models);
        } else {
            return false;
        }

    }

    /**
     * 完成评价 用户确认
     * @param $order
     * @param $must_models
     * @param $transact
     * @return bool
     */
    protected static function _customerAcceptDone(&$order, $must_models = [], $transact = null)
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_CUSTOMER_ACCEPT_DONE);
        return self::_statusChange($order, $status, $must_models, $transact);
    }


    /**
     * 已完成结算
     * @param $order
     * @param $must_models
     * @return bool
     */
    protected static function _payoffDone(&$order, $must_models = [])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_PAYOFF_DONE);
        return self::_statusChange($order, $status, $must_models);
    }


    /**
     * 取消订单
     * @param $order
     * @param $must_models
     * @param $transact
     * @return bool
     */
    protected static function _cancel(&$order, $must_models = [], $transact = null)
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_CANCEL);
        return self::_statusChange($order, $status, $must_models, $transact);
    }

    /**
     * 已归档
     * @param $order
     * @param $must_models
     * @return bool
     */
    protected static function Died(&$order, $must_models = [])
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_DIED);
        return self::_statusChange($order, $status, $must_models);
    }


    /**
     * 修改订单状态
     * @param $order Order
     * @param $status OrderStatusDict
     * @param $must_models array
     * @param $transact
     * @return bool
     */
    private static function _statusChange(&$order, $status, $must_models = [], $transact = null)
    {
        $from = OrderStatusDict::findOne($order->orderExtStatus->order_status_dict_id); //当前订单状态
        $order->setAttributes([
            'order_before_status_dict_id' => $from->id,
            'order_before_status_name' => $from->order_status_name,
            'order_status_dict_id' => $status->id,
            'order_status_name' => $status->order_status_name,
            'order_status_boss' => $status->order_status_boss,
            'order_status_customer' => $status->order_status_customer,
            'order_status_worker' => $status->order_status_worker
        ]);
        $save_models = ['OrderExtStatus', 'OrderStatusHistory'];
        $save_models = array_merge($must_models, $save_models);
        return $order->doSave($save_models, $transact);
    }


}