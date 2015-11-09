<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/11/9
 * Time: 19:59
 */
namespace core\models\order;

use Yii;
use yii\base\Model;

class OrderManualAssign extends Model
{

    /**
     * 获取待人工指派的订单
     * 订单状态为系统指派失败的订单
     * @author lin
     * @param $admin_id 操作人id
     * @param $is_mini_boss bool 是否是小家政获取
     * @return $this|static
     */
    public static function getWaitManualAssignOrder($admin_id, $is_mini_boss = true)
    {
        $flag_send = $is_mini_boss ? 1 : 2; //1小家政可派 2客服可派

        $order = Order::find()->joinWith(['orderExtStatus', 'orderExtFlag'])->where(
            ['>', 'order_booked_begin_time', time()] //服务开始时间大于当前时间
        )->andWhere([ //先查询该管理员正在指派的订单
            'orderExtStatus.order_status_dict_id' => OrderStatusDict::ORDER_MANUAL_ASSIGN_START,
            'orderExtFlag.order_flag_lock' => $admin_id,
            'order_parent_id' => 0
        ])->orderBy(['order_booked_begin_time' => SORT_ASC])->one();
        if (empty($order)) {//如果没有正在指派的订单再查询待指派的订单
            $order = Order::find()->joinWith(['orderExtStatus', 'orderExtFlag'])->where([
                'and',
                ['>', 'order_booked_begin_time', time()], //服务开始时间大于当前时间
                ['orderExtFlag.order_flag_send' => [0, $flag_send]], //0可指派 1客服指派不了 2小家政指派不了
                ['order_parent_id' => 0]
            ])->andWhere([
                'or',
                ['orderExtFlag.order_flag_lock' => 0],
                ['<', 'orderExtFlag.order_flag_lock_time', time() - Yii::$app->params['order']['MANUAL_ASSIGN_lONG_TIME']] //查询超时未解锁的订单
            ])->andWhere([ //系统指派失败的 或者 已支付待指派并且标记不需要系统指派的订单
                'or',
                [
                    'orderExtStatus.order_status_dict_id' => OrderStatusDict::ORDER_SYS_ASSIGN_UNDONE
                ],
                [
                    'orderExtStatus.order_status_dict_id' => OrderStatusDict::ORDER_WAIT_ASSIGN,
                    'orderExtFlag.order_flag_sys_assign' => 0
                ]
            ])->orderBy(['order_booked_begin_time' => SORT_ASC])->one();
            if (!empty($order)) {
                //获取到订单后加锁并置为已开始人工派单的状态
                if ($order->order_is_parent == 1) {
                    $result = OrderStatus::batchManualAssignStart($order->order_batch_code, $admin_id, $is_mini_boss);
                } else {
                    $order->order_flag_lock = $admin_id;
                    $order->order_flag_lock_time = time(); //加锁时间
                    $order->order_flag_send = $order->orderExtFlag->order_flag_send + ($is_mini_boss ? 2 : 1); //指派时先标记是谁指派不了 1客服指派不了 2小家政指派不了
                    $order->admin_id = $admin_id;
                    $result = OrderStatus::manualAssignStart($order, ['OrderExtFlag']);
                }
                if ($result) {
                    OrderPool::remOrderForWorkerPushList($order->id); //从接单大厅中删除此订单
                    return Order::findOne($order->id);
                }
            }
        } else {
            return $order;
        }
        return false;
    }

}