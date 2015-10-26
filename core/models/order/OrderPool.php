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
    const WAIT_ASSIGN_ORDERS_POOL = 'WaitAssignOrdersPool';
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
        Yii::$app->redis->executeCommand('zAdd', [self::WAIT_ASSIGN_ORDERS_POOL, $order->order_booked_begin_time . $order_id, json_encode($redis_order)]);
    }

    /**
     * 把订单移出订单池
     * @param $order_id
     */
    public static function remOrder($order_id)
    {
        $orders = Yii::$app->redis->executeCommand('zrange', [self::WAIT_ASSIGN_ORDERS_POOL, 0, -1]);
        foreach ($orders as $v) {
            $redis_order_item = json_decode($v, true);
            if ($redis_order_item['order_id'] == $order_id) {
                Yii::$app->redis->executeCommand('zrem', [self::WAIT_ASSIGN_ORDERS_POOL, $v]);
            }
        }
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
