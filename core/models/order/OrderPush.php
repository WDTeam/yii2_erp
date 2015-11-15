<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/10/27
 * Time: 19:13
 */

namespace core\models\order;


use dbbase\models\order\OrderExtFlag;
use dbbase\models\order\OrderOtherDict;
use Yii;
use core\models\worker\Worker;
use yii\log\Logger;

class OrderPush extends Order
{

    const WAIT_IVR_PUSH_ORDERS_POOL = 'WAIT_IVR_PUSH_ORDERS_POOL';
    const WAIT_IVR_PUSH_ORDERS_POOL_RECORD = 'WAIT_IVR_PUSH_ORDERS_POOL_RECORD';
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
            if($order->order_booked_worker_id>0 && time() - $order->orderExtStatus->updated_at < Yii::$app->params['order']['ORDER_BOOKED_WORKER_ASSIGN_TIME']){ //先判断有没有指定阿姨
                $workers[] = Worker::getWorkerInfo($order->order_booked_worker_id);
            }elseif (time() - $order->orderExtStatus->updated_at < Yii::$app->params['order']['ORDER_FULL_TIME_WORKER_SYS_ASSIGN_TIME']) {
                //获取全职阿姨
                $workers = Worker::getDistrictFreeWorkerForAutoAssign($order->district_id, $full_time, $order->order_booked_begin_time, $order->order_booked_end_time);
                \Yii::getLogger()->log("获取的全职阿姨数量为:".count($workers), Logger::LEVEL_ERROR,'core');
                foreach ($workers as $w){
                    \Yii::getLogger()->log("获取的全职阿姨为:".$w['id'].','.$w['worker_phone'], Logger::LEVEL_ERROR,'core');
                }
                
                $push_status = $full_time;
                if (empty($workers)) {
                    //没有全职阿姨 获取兼职阿姨
                    $workers = Worker::getDistrictFreeWorkerForAutoAssign($order->district_id, $part_time, $order->order_booked_begin_time, $order->order_booked_end_time);
                    $push_status = $part_time;
                }
            } elseif (time() - $order->orderExtStatus->updated_at < Yii::$app->params['order']['ORDER_PART_TIME_WORKER_SYS_ASSIGN_TIME']) {
                $workers = Worker::getDistrictFreeWorkerForAutoAssign($order->district_id, $part_time, $order->order_booked_begin_time, $order->order_booked_end_time);
                $push_status = $part_time;
            }
            if (!empty($workers)) {
                self::pushToWorkers($order_id, $workers, $push_status);
            } else {//如果查询不到兼职阿姨则系统指派失败
                Order::sysAssignUndone($order_id);
                $push_status = 1001;
            }
        } else {
            //状态不是智能指派中直接从订单池中删除
            OrderPool::remOrder($order_id);
            $push_status = 1001;
        }

        $order = OrderSearch::getOne($order_id);
        $order_flag = OrderExtFlag::findOne($order_id);
        $redis_order = [
            'order_code' => $order->order_code,
            'created_at' => $order->created_at,
            'updated_at' => $order->updated_at,
            'assign_start_time' => $order->orderExtStatus->updated_at,
            'jpush' => $order_flag->order_flag_worker_jpush,
            'ivr' => $order_flag->order_flag_worker_ivr,
            'status'=> $push_status,
        ];

        return $redis_order;
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
        $order = OrderSearch::getOne($order_id);
        $is_ivr_worker_ids = OrderWorkerRelation::getWorkerIdsByOrderIdAndStatusId($order_id, OrderOtherDict::NAME_IVR_PUSH_SUCCESS);
        \Yii::getLogger()->log("已经推送过ivr的阿姨数量为:".count($is_ivr_worker_ids), Logger::LEVEL_ERROR,'core');
        foreach($is_ivr_worker_ids as $id){
            \Yii::getLogger()->log("已经推送过ivr的阿姨为:".$id['worker_id'], Logger::LEVEL_ERROR,'core');
        }
        
        $is_jpush_worker_ids = OrderWorkerRelation::getWorkerIdsByOrderIdAndStatusId($order_id, OrderOtherDict::NAME_JPUSH_PUSH_SUCCESS);
        $wait_ivr_worker_list = Yii::$app->redis->executeCommand('lRange', [self::WAIT_IVR_PUSH_ORDERS_POOL_RECORD . '_' . $order_id, 0,-1]);
        foreach ($workers as $v) {
            if (!in_array($v['id'], $wait_ivr_worker_list)) { //判断该阿姨有没有推送过该订单，防止重复推送。
                //把该推送ivr的阿姨放入该订单的队列中
                Yii::$app->redis->executeCommand('rPush', [self::WAIT_IVR_PUSH_ORDERS_POOL . '_' . $order_id, json_encode(['id' => $v['id'], 'worker_phone' => $v['worker_phone']])]);
                Yii::$app->redis->executeCommand('rPush', [self::WAIT_IVR_PUSH_ORDERS_POOL_RECORD . '_' . $order_id, $v['id']]);
                $ivr_flag = true;
            }
            if (!in_array($v['id'], $is_jpush_worker_ids)) {
                OrderPool::addOrderToWorkerPushList($order_id,$v['id']); //把订单添加到接单大厅
                $result = Yii::$app->jpush->push(["worker_{$v['worker_phone']}"],'',json_encode(["order_code"=>$order->order_code,"msg"=>"阿姨，您有一个{$order->order_money}元的待抢订单，请及时确认接单。"]));
                if (isset($result->isOk)) {
                    OrderWorkerRelation::jpushPushSuccess($order_id, $v['id'], 1);
                    $jpush_flag = true;
                }else{
                    OrderWorkerRelation::jpushPushFailure($order_id, $v['id'], 1);
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
            \Yii::getLogger()->log("推送订单:".$order_id, Logger::LEVEL_ERROR,'core');
            if (!empty($worker)) {
                $week = ['日','一','二','三','四','五','六'];
                $range =  date('H点i分', $order->order_booked_begin_time).'至'. date('H点i分', $order->order_booked_end_time);
                $ivr_msg = "服务时间是:" . date('y年m月d日', $order->order_booked_begin_time) . "，星期".$week[date('w', $order->order_booked_begin_time)]."，".$range
                    ."，时长" . $order->order_booked_count. "小时。服务地址是：{$order->order_address}！";
                $result = Yii::$app->ivr->send($worker['worker_phone'], 'pushToWorker_' . $order_id, $ivr_msg);
                if (isset($result['result']) && $result['result'] == 0) {
                    OrderWorkerRelation::ivrPushSuccess($order_id, $worker['id'], 1);
                } else {
                    OrderWorkerRelation::ivrPushFailure($order_id, $worker['id'], 1);
                }
            }
        } else {
            //移除该订单的队列
            Yii::$app->redis->executeCommand('del', [self::WAIT_IVR_PUSH_ORDERS_POOL . '_' . $order_id]);
            Yii::$app->redis->executeCommand('del', [self::WAIT_IVR_PUSH_ORDERS_POOL_RECORD . '_' . $order_id]);
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
        $order->admin_id = 1;
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