<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/11/9
 * Time: 19:59
 */
namespace core\models\order;

use core\models\worker\Worker;
use Yii;
use yii\base\Model;

class OrderManualAssign extends Model
{

    /**
     * 获取待客服指派的订单
     * 订单状态为系统指派失败的订单
     * @author lin
     * @param $admin_id 操作人id
     * @return $this|static
     */
    public static function getWaitAssignOrder($admin_id)
    {
        $result_order = Order::find()->joinWith(['orderExtStatus', 'orderExtFlag'])->where(
            ['>', 'order_booked_begin_time', time()] //服务开始时间大于当前时间
        )->andWhere([ //先查询该管理员正在指派的订单
            'orderExtStatus.order_status_dict_id' => OrderStatusDict::ORDER_MANUAL_ASSIGN_START,
            'orderExtFlag.order_flag_lock' => $admin_id,
            'order_parent_id' => 0
        ])->orderBy(['order_booked_begin_time' => SORT_ASC])->one();
        if (empty($result_order)) {//如果没有正在指派的订单再查询待指派的订单
            $order = Order::find()->joinWith(['orderExtStatus', 'orderExtFlag'])->where([
                'and',
                ['>', 'order_booked_begin_time', time()], //服务开始时间大于当前时间
                ['orderExtFlag.order_flag_send' => [0, 2]], //0可指派 1客服指派不了 2小家政指派不了
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
                    $result = OrderStatus::batchManualAssignStart($order->order_batch_code, $admin_id, false);
                } else {
                    $order->order_flag_lock = $admin_id;
                    $order->order_flag_lock_time = time(); //加锁时间
                    $order->order_flag_send = $order->orderExtFlag->order_flag_send + 1; //指派时先标记是谁指派不了 1客服指派不了 2小家政指派不了
                    $order->admin_id = $admin_id;
                    $result = OrderStatus::manualAssignStart($order, ['OrderExtFlag']);
                }
                if ($result) {
                    OrderPool::remOrderForWorkerPushList($order->id); //从接单大厅中删除此订单
                    $result_order = Order::findOne($order->id);
                }
            }
        }

        return $result_order;
    }

    /**
     * 获取待小家政指派的订单
     * 订单状态为系统指派失败的订单
     * @author lin
     * @param $admin_id 操作人id
     * @param $district_ids 商圈id
     * @return $this|static
     */
    public static function getMiniBossWaitAssignOrder($admin_id,$district_ids)
    {

        $result_order = Order::find()->joinWith(['orderExtStatus', 'orderExtFlag'])->where(
            ['>', 'order_booked_begin_time', time()] //服务开始时间大于当前时间
        )->andWhere([ //先查询该管理员正在指派的订单
            'orderExtStatus.order_status_dict_id' => OrderStatusDict::ORDER_MANUAL_ASSIGN_START,
            'orderExtFlag.order_flag_lock' => $admin_id,
            'order_parent_id' => 0
        ])->orderBy(['order_booked_begin_time' => SORT_ASC])->one();
        if (empty($result_order)) {//如果没有正在指派的订单再查询待指派的订单
            $order = Order::find()->joinWith(['orderExtStatus', 'orderExtFlag'])->where([
                'and',
                ['>', 'order_booked_begin_time', time()], //服务开始时间大于当前时间
                ['orderExtFlag.order_flag_send' => [0, 1]], //0可指派 1客服指派不了 2小家政指派不了
                ['order_parent_id' => 0],
                ['district_id' => $district_ids]
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

                $workers = self::getCanAssignWorkerList($order);
                if(!isset($workers['code']) && count($workers)>0) { //如果小家政有可接单阿姨
                    if ($order->order_is_parent == 1) {
                        $result = OrderStatus::batchManualAssignStart($order->order_batch_code, $admin_id, true);
                    } else {
                            $order->order_flag_lock = $admin_id;
                            $order->order_flag_lock_time = time(); //加锁时间
                            $order->order_flag_send = $order->orderExtFlag->order_flag_send + 2; //指派时先标记是谁指派不了 1客服指派不了 2小家政指派不了
                            $order->admin_id = $admin_id;
                            $result = OrderStatus::manualAssignStart($order, ['OrderExtFlag']);
                    }
                    if ($result) {
                        OrderPool::remOrderForWorkerPushList($order->id); //从接单大厅中删除此订单
                        $result_order = Order::findOne($order->id);
                    }
                }
            }
        }

        return $result_order;

    }

    /**
     * 获取待指派订单数量
     * @param $district_ids 商圈id
     * @return int|string
     */
    public static function getWaitAssignOrdersCount($district_ids = null)
    {
        $order_flag_send = [0,2];
        $district_where = [];
        if(!empty($district_ids)){
            $order_flag_send = [0,1];
            $district_where = ['district_id' => $district_ids];
        }
        return Order::find()->joinWith(['orderExtStatus', 'orderExtFlag'])->where([
            'and',
            ['>', 'order_booked_begin_time', time()], //服务开始时间大于当前时间
            ['orderExtFlag.order_flag_send' => $order_flag_send], //0可指派 1客服指派不了 2小家政指派不了
            ['order_parent_id' => 0],
            $district_where
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
        ])->count();
    }


    /**
     * 获取可指派阿姨的列表
     * @param $order
     * @return array
     */
    public static function getCanAssignWorkerList($order)
    {
        //根据商圈获取阿姨列表 第二个参数 1全职 2兼职
        try {
            $times = [['orderBookBeginTime' => $order->order_booked_begin_time, 'orderBookEndTime' => $order->order_booked_end_time]];
            if ($order->order_is_parent == 1) {
                $childs = OrderSearch::getChildOrder($order->id);
                foreach ($childs as $child) {
                    $times[] = ['orderBookBeginTime' => $child->order_booked_begin_time, 'orderBookEndTime' => $child->order_booked_end_time];
                }
            }
            return array_merge(Worker::getDistrictCycleFreeWorker($order->district_id, 1, $times), Worker::getDistrictCycleFreeWorker($order->district_id, 2, $times));

        } catch (Exception $e) {
            return ['code' => 500, 'msg' => '获取阿姨列表接口异常！'];
        }
    }

    /**
     * 根据名称和手机号搜索阿姨列表
     * @param $worker_name
     * @param $phone
     * @return array
     */
    public static function searchWorker($worker_name,$phone)
    {
        try {
            return Worker::searchWorker($worker_name, $phone);
        } catch (Exception $e) {
            return false;
        }


    }


}