<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/11/4
 * Time: 18:25
 */

namespace core\models\order;

use dbbase\models\order\OrderExtPay;

class OrderPop extends Model
{
    const POP_STATUS_CANCEL = -1;
    const POP_STATUS_ASSIGN = 2;
    const POP_STATUS_DONE = 5;

    const VERSION = 1.0;

    /**
     * 创建新订单
     * @param $attributes [
     *  string $order_ip 下单IP地址 必填
     *  integer $order_service_item_id 服务项id 必填
     *  integer $order_src_id 订单来源id 必填
     *  string $channel_id 下单渠道 必填
     *  int $order_booked_begin_time 预约开始时间 必填
     *  int $order_booked_end_time 预约结束时间 必填
     *  int $address_id 客户地址id 必填
     *  int $customer_id 客户id 必填
     *  string $order_pop_order_code 第三方订单号 必填
     *  string $order_pop_group_buy_code 第三方团购号
     *  int $order_pop_order_money 第三方预付金额 必填
     * ]
     * @return bool
     */
    public static function createNew($attributes)
    {
        $order = new Order();
        $attributes_keys = [
            'order_ip','order_service_item_id','order_src_id','channel_id',
            'order_booked_begin_time','order_booked_end_time','address_id',
            'customer_id', 'order_pop_order_code', 'order_pop_group_buy_code', 'order_pop_order_money'
        ];
        $attributes_required = [
            'order_ip','order_service_item_id','order_src_id','channel_id',
            'order_booked_begin_time','order_booked_end_time','address_id',
            'customer_id','order_pop_order_code','order_pop_order_money'
        ];
        foreach($attributes as $k=>$v){
            if(!in_array($k,$attributes_keys)){
                unset($attributes[$k]);
            }
        }
        foreach($attributes_required as $v){
            if(!isset($attributes[$v])){
                $order->addError($v,Order::getAttributeLabel($v).'为必填项！');
                return false;
            }
        }
        $attributes['order_pay_type'] = OrderExtPay::ORDER_PAY_TYPE_POP;
        $attributes['admin_id'] = 1;
        $order->createNew($attributes);
        return $order;
    }

    /**
     * 第三方取消订单同步到boss
     * @param $order_code
     * @param $cause
     * @param $memo
     * @return bool
     */
    public static function cancel($order_code,$cause,$memo)
    {
        $order = OrderSearch::getOneByCode($order_code);
        return Order::cancel($order->id,1,$cause,$memo,true);
    }

    /** *********************以下为订单部分调用****************************** */

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
        $url = \Yii::$app->params['order']['pop']['api_url'].'push-order-status';
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