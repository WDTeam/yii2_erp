<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/10/10
 * Time: 13:39
 */
namespace core\models\order;


use Yii;

class OrderStatusHistory extends \dbbase\models\order\OrderStatusHistory
{

    /**
     * 获取订单已支付待指派的时间
     * @param $order_id
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getOrderStatusHistory($order_id)
    {
       return  self::find()->where([
           'order_id'=>$order_id,
           'order_status_dict_id'=>OrderStatusDict::ORDER_WAIT_ASSIGN
       ])->select('order_id,created_at,order_before_status_dict_id,order_status_dict_id')->one();
    }


    /**
     * 查询状态历史
     * @param $order_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findByOrderId($order_id)
    {
        return self::find()->where(["order_id" => $order_id])->orderBy(["order_id" => SORT_DESC])->all();
    }
   
}