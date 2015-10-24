<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/10/24
 * Time: 14:18
 */
namespace core\models\order;


use common\models\OrderStatusDict;
use Yii;
use core\models\worker\Worker;

class OrderPush extends Order
{

    const WAIT_IVR_PUSH_ORDERS_POOL = 'WAIT_IVR_PUSH_ORDERS_POOL';
    const PUSH_ORDER_LOCK = 'PUSH_ORDER_LOCK';

    /**
     * 智能推送
     * @param $order_id
     * @return array
     *
     *
     */
    public static function push($order_id)
    {
        $order = OrderSearch::getOne($order_id);

        $full_time = 1; //全职
        $part_time = 2; //兼职
        $push_status = 0; //推送状态
        if ($order->orderExtStatus->order_status_dict_id == OrderStatusDict::ORDER_SYS_ASSIGN_START) { //开始系统指派的订单
            if($order->order_booked_worker_id>0 && time() - $order->orderExtStatus->updated_at < 900){ //先判断有没有指定阿姨
                $workers[] = Worker::getWorkerInfo($order->order_booked_worker_id);
            }elseif (time() - $order->orderExtStatus->updated_at < 300) { //TODO 5分钟内的订单推送给全职阿姨 5分钟需要配置
                //获取全职阿姨
                $workers = Worker::getDistrictFreeWorker($order->district_id, $full_time, $order->order_booked_begin_time, $order->order_booked_end_time);
                $push_status = $full_time;
                if (empty($workers)) {
                    //没有全职阿姨 获取兼职阿姨
                    $workers = Worker::getDistrictFreeWorker($order->district_id, $part_time, $order->order_booked_begin_time, $order->order_booked_end_time);
                    $push_status = $part_time;
                }
            } elseif (time() - $order->orderExtStatus->updated_at < 900) { //TODO 15分钟内的订单推送给兼职阿姨 15分钟需要配置
                $workers = Worker::getDistrictFreeWorker($order->district_id, $part_time, $order->order_booked_begin_time, $order->order_booked_end_time);
                $push_status = $part_time;
            }
            if (!empty($workers)) {
                self::pushToWorkers($order_id, $workers, $push_status);
            } else {//如果查询不到兼职阿姨则系统指派失败
                Order::sysAssignUndone($order_id);
            }
        } else {
            //状态不是智能指派中直接从订单池中删除
            OrderPool::remOrder($order_id);
        }

        $order = OrderSearch::getOne($order_id);
        return ['order_id' => $order->id, 'created_at' => $order->orderExtStatus->updated_at, 'jpush' => $order->orderExtFlag->order_flag_worker_jpush, 'ivr' => $order->orderExtFlag->order_flag_worker_ivr, 'push_status'=>$push_status];
    }

    /**
     * 推送给阿姨
     * @param $order_id
     * @param $workers
     * @param $identity
     */
    public static function pushToWorkers($order_id, $workers, $identity)
    {
        $ivr_flag = false;
        $jpush_flag = false;
        $is_ivr_worker_ids = OrderWorkerRelation::getWorkerIdsByOrderIdAndStatus($order_id, 'IVR已推送');
        $is_jpush_worker_ids = OrderWorkerRelation::getWorkerIdsByOrderIdAndStatus($order_id, 'JPUSH已推送');
        foreach ($workers as $v) {
            if (!in_array($v['id'], $is_ivr_worker_ids)) { //判断该阿姨有没有推送过该订单，防止重复推送。
                //把该推送ivr的阿姨放入该订单的队列中
                Yii::$app->redis->executeCommand('rPush', [self::WAIT_IVR_PUSH_ORDERS_POOL . '_' . $order_id, json_encode(['id' => $v['id'], 'worker_phone' => $v['worker_phone']])]);
                $ivr_flag = true;
            }
            if (!in_array($v['id'], $is_jpush_worker_ids)) {
                $result = Yii::$app->jpush->push(["worker_{$v['id']}"], '订单来啦！'); //TODO 发送内容
                if (isset($result->isOK)) {
                    $worker_id = intval(str_replace('worker_', '', $v));
                    OrderWorkerRelation::addOrderWorkerRelation($order_id, $worker_id, '', 'JPUSH已推送', 1);
                    $jpush_flag = true;
                }
            }
        }
        if ($ivr_flag) {
            self::workerIVRPushFlag($order_id); //标记ivr推送
        }
        if ($jpush_flag) {
            self::workerJPushFlag($order_id); //标记极光推送
        }

        //重新加入订单池
        OrderPool::updateOrder($order_id,$identity);

        self::ivrPushToWorker($order_id); //开始ivr推送
    }

    /**
     * ivr推送给单个阿姨
     * @param $order_id
     */
    public static function ivrPushToWorker($order_id)
    {
        $order = OrderSearch::getOne($order_id);
        if ($order->orderExtStatus->order_status_dict_id == OrderStatusDict::ORDER_SYS_ASSIGN_START) { //开始系统指派的订单
            $worker = json_decode(Yii::$app->redis->executeCommand('lPop', [self::WAIT_IVR_PUSH_ORDERS_POOL . '_' . $order_id]), true);
            if (!empty($worker)) {
                $result = Yii::$app->ivr->send($worker['worker_phone'], 'pushToWorker_' . $order_id, "开始时间" . date('y年m月d日H点', $order->order_booked_begin_time) . "，时长" . intval($order->order_booked_count / 60) . "个" . ($order->order_booked_count % 60 > 0 ? "半" : "") . "小时，地址{$order->order_address}！"); //TODO 发送内容
                if (isset($result['result']) && $result['result'] == 0) {
                    OrderWorkerRelation::addOrderWorkerRelation($order_id, $worker['id'], '', 'IVR已推送', 1);
                } else {
                    OrderWorkerRelation::addOrderWorkerRelation($order_id, $worker['id'], '', 'IVR推送失败', 1);
                }
            }
        } else {
            //移除该订单的队列
            Yii::$app->redis->executeCommand('del', [self::WAIT_IVR_PUSH_ORDERS_POOL . '_' . $order_id]);
        }
    }

    /**
     * 标记订单已发送短信给阿姨
     * @param $order_id
     * @return bool
     */
    public static function workerSMSPushFlag($order_id)
    {
        $order = OrderSearch::getOne($order_id);
        $order->order_flag_worker_sms = $order->orderExtFlag->order_flag_worker_sms + 1;
        return $order->doSave(['OrderExtFlag']);
    }

    /**
     * 标记订单已推送极光给阿姨
     * @param $order_id
     * @return bool
     */
    public static function workerJPushFlag($order_id)
    {
        $order = OrderSearch::getOne($order_id);
        $order->admin_id = 1;
        $order->order_flag_worker_jpush = $order->orderExtFlag->order_flag_worker_jpush + 1;
        return $order->doSave(['OrderExtFlag']);
    }

    /**
     * 标记订单已发送IVR给阿姨
     * @param $order_id
     * @return bool
     */
    public static function workerIVRPushFlag($order_id)
    {
        $order = OrderSearch::getOne($order_id);
        $order->admin_id = 1;
        $order->order_flag_worker_ivr = $order->orderExtFlag->order_flag_worker_ivr + 1;
        return $order->doSave(['OrderExtFlag']);
    }

}