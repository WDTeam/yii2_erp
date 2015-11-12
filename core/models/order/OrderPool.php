<?php

/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/9/23
 * Time: 18:30
 */
namespace core\models\order;


use dbbase\models\order\OrderExtFlag;
use Yii;
use yii\base\Model;

class OrderPool extends Model
{
    const WAIT_ASSIGN_ORDERS_POOL = 'WAIT_ASSIGN_ORDERS_POOL';
    const PUSH_WORKER_ORDERS = 'PUSH_WORKER_ORDERS';
    const PUSH_BOOKED_WORKER_ORDERS = 'PUSH_BOOKED_WORKER_ORDERS';
    const PUSH_ORDER_WORKERS = 'PUSH_ORDER_WORKERS';

    /**
     * 添加订单到推送的阿姨列表
     * @param $order_id
     * @param $worker_id
     */
    public static function addOrderToWorkerPushList($order_id,$worker_id)
    {
        $order = OrderSearch::getOne($order_id);
        $redis_order = [
            'order_id' => $order_id,
            'order_code' => $order->order_code,
            'batch_code' => $order->order_batch_code,
            'channel_name' => $order->order_channel_name,
            'booked_count' => $order->order_booked_count,
            'address' => $order->order_address,
            'need' => $order->orderExtCustomer->order_customer_need,
            'money' => $order->order_money,
            'lng' => $order->order_lng,
            'lat' => $order->order_lat,
            'is_booked_worker' => ($order->order_booked_worker_id==$worker_id)?"true":"false",
            'order_time' => [$order->order_booked_begin_time.'-'.$order->order_booked_end_time]
        ];
        if($order->order_is_parent==1){
            $child_list = OrderSearch::getChildOrder($order_id);
            foreach($child_list as $child){
                $redis_order['order_time'][] = $child->order_booked_begin_time.'-'.$child->order_booked_end_time;
                $redis_order['money'] += $child->order_money;
            }
        }
        if($order->order_booked_worker_id==$worker_id){
            Yii::$app->redis->executeCommand('zAdd', [self::PUSH_BOOKED_WORKER_ORDERS.'_'.$worker_id, $order_id, json_encode($redis_order)]);
        }else {
            Yii::$app->redis->executeCommand('zAdd', [self::PUSH_WORKER_ORDERS . '_' . $worker_id, $order_id, json_encode($redis_order)]);
        }
        Yii::$app->redis->executeCommand('zAdd', [self::PUSH_ORDER_WORKERS.'_'.$order_id, $worker_id, $worker_id]);
    }

    /**
     * 重新添加到推送的阿姨列表
     * @param $order_id
     */
    public static function reAddOrderToWorkerPushList($order_id){
        $worker_ids = Yii::$app->redis->executeCommand('zRange', [self::PUSH_ORDER_WORKERS.'_'.$order_id, 0, -1]);
        foreach($worker_ids as $worker_id) {
           self::addOrderToWorkerPushList($order_id,$worker_id);
        }
    }

    /**
     * 从推送的阿姨列表中把订单删除
     * @param $order_id
     * @param bool $remPushOrderWorkers 是否永久从接单大厅中移除 移除后不可恢复
     */
    public static function remOrderForWorkerPushList($order_id,$remPushOrderWorkers = false)
    {
        $worker_ids = Yii::$app->redis->executeCommand('zRange', [self::PUSH_ORDER_WORKERS.'_'.$order_id, 0, -1]);
        foreach($worker_ids as $worker_id) {
            Yii::$app->redis->executeCommand('zRemRangeByScore', [self::PUSH_WORKER_ORDERS . '_' . $worker_id, $order_id, $order_id]);
            Yii::$app->redis->executeCommand('zRemRangeByScore', [self::PUSH_BOOKED_WORKER_ORDERS . '_' . $worker_id, $order_id, $order_id]);
        }
        if($remPushOrderWorkers){
            Yii::$app->redis->executeCommand('zRemRangeByRank', [self::PUSH_ORDER_WORKERS.'_'.$order_id, 0, -1]);
        }
    }

    /**
     * 返回推送给阿姨的订单列表
     * @param $worker_id
     * @param int $page_size
     * @param int $page
     * @return array
     */
    public static function getOrdersFromWorkerPushList($worker_id,$page_size=20,$page=1)
    {
        $begin = ($page-1)*$page_size;
        $end = $begin+$page_size-1;
        $orders = Yii::$app->redis->executeCommand('zRange', [self::PUSH_BOOKED_WORKER_ORDERS.'_'.$worker_id, $begin, $end]);
        if(count($orders)<$page_size) {
            $begin = $begin + count($orders) - self::getOrdersCountFromWorkerPushList($worker_id,true);
            $end = $begin+$page_size-1-count($orders);
            $orders += Yii::$app->redis->executeCommand('zRange', [self::PUSH_WORKER_ORDERS . '_' . $worker_id, $begin, $end]);
        }
        $order_list = [];
        foreach($orders as $order){
            $order_list[] = json_decode($order,true);
        }
        return $order_list;
    }

    /**
     * 返回推送给阿姨的订单总数
     * @param $worker_id
     * @return mixed
     * @param bool $is_booked
     */
    public static function getOrdersCountFromWorkerPushList($worker_id,$is_booked=false)
    {
        if($is_booked){
            return Yii::$app->redis->executeCommand('zCard', [self::PUSH_BOOKED_WORKER_ORDERS.'_'.$worker_id]);
        }else {
            return Yii::$app->redis->executeCommand('zCard', [self::PUSH_WORKER_ORDERS . '_' . $worker_id]);
        }
    }


    /**
     * 把订单放入订单池
     * @param $order_id
     * @param $worker_identity
     */
    public static function addOrder($order_id,$worker_identity=0)
    {
        $order = OrderSearch::getOne($order_id);
        $order_flag = OrderExtFlag::findOne($order_id);
        $redis_order = [
            'order_id' => $order_id,
            'order_code' => $order->order_code,
            'created_at' => $order->created_at,
            'assign_start_time' => $order->orderExtStatus->updated_at,
            'jpush' => $order_flag->order_flag_worker_jpush,
            'ivr' => $order_flag->order_flag_worker_ivr,
            'worker_identity'=> $worker_identity,
        ];
        Yii::$app->redis->executeCommand('zAdd', [self::WAIT_ASSIGN_ORDERS_POOL, $order_id, json_encode($redis_order)]);
    }

    /**
     * 把订单移出订单池
     * @param $order_id
     */
    public static function remOrder($order_id)
    {
        Yii::$app->redis->executeCommand('zRemRangeByScore', [self::WAIT_ASSIGN_ORDERS_POOL, $order_id,$order_id]);
    }

    /**
     * 重新加入订单池
     * @param $order_id
     * @param int $worker_identity
     */
    public static function updateOrder($order_id,$worker_identity=0)
    {
        //把订单从订单池中移除
        self::remOrder($order_id);
        //重新加入订单池
        self::addOrder($order_id,$worker_identity);
    }
}
