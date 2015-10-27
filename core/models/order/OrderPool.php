<?php

/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/9/23
 * Time: 18:30
 */
namespace core\models\order;


use Yii;
use yii\base\Model;

class OrderPool extends Model
{
    const WAIT_ASSIGN_ORDERS_POOL = 'WAIT_ASSIGN_ORDERS_POOL';
    const PUSH_WORKER_ORDERS = 'PUSH_WORKER_ORDERS';
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
            'batch_code' => $order->order_batch_code,
            'booked_begin_time' => $order->order_booked_begin_time,
            'booked_end_time' => $order->order_booked_end_time,
            'channel_name' => $order->order_channel_name,
            'booked_count' => $order->order_booked_count,
            'address' => $order->order_address,
            'need' => $order->orderExtCustomer->order_customer_need
        ];
        Yii::$app->redis->executeCommand('zAdd', [self::PUSH_WORKER_ORDERS.'_'.$worker_id, $order_id, json_encode($redis_order)]);
        Yii::$app->redis->executeCommand('zAdd', [self::PUSH_ORDER_WORKERS.'_'.$order_id, $worker_id, $worker_id]);
    }

    /**
     * 从推送的阿姨列表中把订单删除
     * @param $order_id
     */
    public static function remOrderForWorkerPushList($order_id)
    {
        $worker_ids = Yii::$app->redis->executeCommand('zRange', [self::PUSH_ORDER_WORKERS.'_'.$order_id, 0, -1]);
        foreach($worker_ids as $worker_id) {
            Yii::$app->redis->executeCommand('zRemRangeByScore', [self::PUSH_WORKER_ORDERS . '_' . $worker_id, $order_id, $order_id]);
        }
    }

    /**
     * 返回推送给阿姨的订单列表
     * @param $worker_id
     * @param int $page_size
     * @param int $page
     * @return mixed
     */
    public static function getOrdersFromWorkerPushList($worker_id,$page_size=20,$page=1)
    {
        $begin = ($page-1)*$page_size;
        $end = $begin+$page_size-1;
        return Yii::$app->redis->executeCommand('zRange', [self::PUSH_WORKER_ORDERS.'_'.$worker_id, $begin, $end]);

    }

    /**
     * 返回推送给阿姨的订单总数
     * @param $worker_id
     * @return mixed
     */
    public static function getOrdersCountFromWorkerPushList($worker_id)
    {

        return Yii::$app->redis->executeCommand('zCard', [self::PUSH_WORKER_ORDERS.'_'.$worker_id]);

    }

    /**
     * 把订单放入订单池
     * @param $order_id
     * @param $worker_identity
     */
    public static function addOrder($order_id,$worker_identity=0)
    {
        $order = OrderSearch::getOne($order_id);
        $redis_order = [
            'order_id' => $order_id,
            'created_at' => $order->orderExtStatus->updated_at,
            'jpush' => $order->orderExtFlag->order_flag_worker_jpush,
            'ivr' => $order->orderExtFlag->order_flag_worker_ivr,
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
