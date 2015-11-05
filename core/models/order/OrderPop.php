<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/11/4
 * Time: 18:25
 */

namespace core\models\order;


class OrderPop extends Model
{
    const POP_STATUS_CANCEL = -1;
    const POP_STATUS_ASSIGN = 2;
    const POP_STATUS_DONE = 5;

    const VERSION = 1.0;

    public static function cancelToPop($order)
    {
        return self::_pushStatus($order,self::POP_STATUS_CANCEL);
    }

    public static function assignToPop($order)
    {
        return self::_pushStatus($order,self::POP_STATUS_ASSIGN);
    }

    public static function doneToPop($order)
    {
        return self::_pushStatus($order,self::POP_STATUS_DONE);
    }

    private static function _pushStatus($order,$status)
    {
        $url = \Yii::$app->params['order_pop']['api_url'].'push-order-status';
        $data[order_id] = $order->order_code;
        $data[status] = $status;
        $data[platform_version] = self::VERSION;
        $data[sign] = self::_getSign($data);
        $json = self::_postCurl($url,$data);
        $result = json_decode($json,true);
        return (isset($result['code'])&&$result['code']==0);
    }

    private static function _getSign($prams)
    {
        $key = 'pop_to_boss';
        $sign = '';
        ksort($prams);
        foreach ($prams as $k => $v) {
            $sign .= $k . $v;
        }
        return md5($sign . $key);
    }

    private static function _postCurl($url,$post_data="")
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data ); //$data是每个接口的json字符串
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  //不加会包证书问题
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  //不加会包证书问题
        curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: application/json; charset=utf-8'));
        return curl_exec ($ch);
    }
}