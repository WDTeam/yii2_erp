<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/10/10
 * Time: 13:39
 */
namespace core\models\order;


use Yii;
use yii\base\Event;
use yii\base\Exception;
use yii\base\Model;
class OrderStatusHistory extends \common\models\OrderStatusHistory
{

    /**
     * 在线支付完后调用修改订单状态
     * @param $order_id int 订单id
     * @param $admin_id int  后台管理员id 系统0 客户1
     * @param $pay_channel_id int  支付渠道id
     * @param $order_pay_channel_name string 支付渠道名称
     * @param $order_pay_flow_num string 支付流水号
     * @return bool
     */
    public static function getOrderStatusHistory($order_id)
    {
       return  self::find()->where((['order_id'=>$order_id,'order_before_status_dict_id'=>2]))->select('order_id,created_at,order_before_status_dict_id,order_status_dict_id')->one();
    }

   
}