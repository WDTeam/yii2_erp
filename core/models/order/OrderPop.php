<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/11/4
 * Time: 18:25
 */

namespace core\models\order;



use yii\base\Model;

class OrderPop extends Model
{
    const POP_STATUS_CANCEL = -1;
    const POP_STATUS_ASSIGN = 2;
    const POP_STATUS_DONE = 5;

    const VERSION = 1.0;

    /**
     * 取消订单同步到POP
     * @param $order
     * @return bool
     */
    public static function cancelToPop($order)
    {
        return self::_pushStatus($order,self::POP_STATUS_CANCEL);
    }

    /**
     * 指派完成同步到POP
     * @param $order
     * @return bool
     */
    public static function assignDoneToPop($order)
    {
        return self::_pushStatus($order,self::POP_STATUS_ASSIGN);
    }

    /**
     * 订单完成同步到POP
     * @param $order
     * @return bool
     */
    public static function serviceDoneToPop($order)
    {
        return self::_pushStatus($order,self::POP_STATUS_DONE);
    }

    private static function _pushStatus($order,$status)
    {
        $url = \Yii::$app->params['order_pop']['api_url'].'push-order-status';
        $data["order_code"] = $order->order_code;
        $data["status"] = $status;
        $data["platform_version"] = self::VERSION;
        $data["sign"] = self::_getSign($data);
        $json = file_get_contents($url.'?'.http_build_query($data));
        $result = json_decode($json,true);
        return (isset($result['code'])&&$result['code']==0);
    }

    private static function _getSign($prams)
    {
        $key = 'pop_to_boss';
        $sign = '';
        ksort($prams);
        foreach ($prams as $k => $v) {
            if($k != $sign) {
                $sign .= $k . $v;
            }
        }
        return md5($sign . $key);
    }
}