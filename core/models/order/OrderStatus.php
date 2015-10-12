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
use yii\base\Model;
use common\models\OrderStatusDict;

class OrderStatus extends Model
{

    /**
     * 变更为已支付待指派状态
     * @param $order
     * @param $must_models
     * @return bool
     */
    public static function payment(&$order,$must_models){
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_WAIT_ASSIGN); //变更为已支付待指派状态
        if(self::statusChange($order,$status,$must_models)){
            //支付成功 把订单放入订单池
            Yii::$app->trigger('addOrderToPool', new Event(['sender' => $order]));
            return true;
        }
        return false;
    }

    /**
     * 开始智能指派
     * @param $order
     * @param $must_models
     * @return bool
     */
    public static function sysAssignStart(&$order,$must_models)
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_SYS_ASSIGN_START);
        return self::statusChange($order,$status,$must_models);
    }

    /**
     * 完成智能指派
     * @param $order
     * @param $must_models
     * @return bool
     */
    public static function sysAssignDone(&$order,$must_models)
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_SYS_ASSIGN_DONE);
        return self::statusChange($order,$status,$must_models);
    }

    /**
     * 智能指派失败
     * @param $order
     * @param $must_models
     * @return bool
     */
    public static function sysAssignUndone(&$order,$must_models)
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_SYS_ASSIGN_UNDONE);
        return self::statusChange($order,$status,$must_models);
    }

    /**
     * 开始人工指派
     * @param $order
     * @param $must_models
     * @return bool
     */
    public static function manualAssignStart(&$order,$must_models)
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_MANUAL_ASSIGN_START);
        return self::statusChange($order,$status,$must_models);
    }

    /**
     * 完成人工指派
     * @param $order
     * @param $must_models
     * @return bool
     */
    public static function manualAssignDone(&$order,$must_models)
    {
        $status = OrderStatusDict::findOne(OrderStatusDict::ORDER_MANUAL_ASSIGN_DONE);
        return self::statusChange($order,$status,$must_models);
    }






    /**
     * 修改订单状态
     * @param $order Order
     * @param $status OrderStatusDict
     * @param $must_models array
     * @return bool
     */
    public static function statusChange(&$order, $status, $must_models=[])
    {
        $from = OrderStatusDict::findOne($order->orderExtStatus->order_status_dict_id); //当前订单状态
        $order->setAttributes([
            'order_before_status_dict_id' => $from->id,
            'order_before_status_name' => $from->order_status_name,
            'order_status_dict_id' => $status->id,
            'order_status_name' => $status->order_status_name
        ]);
        $save_models = ['OrderExtStatus','OrderStatusHistory'];
        $save_models = array_merge($must_models,$save_models);
        return $order->doSave($save_models);
    }
}