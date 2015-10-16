<?php
/**
 * Created by PhpStorm.
 * User: lsq
 * Date: 15-10-16
 * Time: 下午7:57
 */
require_once dirname(__FILE__)."/common.php";
class zhidahao_class
{
    public function __construct(){}

    public function get($param){

        //初始化参数
        $params = array(
            'sp_no' => zhidahao::SP_NO,
            'order_no' => $param['out_trade_no'],
            'total_amount' => $param['general_pay_money'],
            'goods_name' => $param['subject'],
            'return_url' => $param['return_url'],
            'page_url' => $param['page_url'],
            'detail' => json_encode($param['detail']),
            'order_source_url' => $param['order_source_url'],
            'customer_name' => $param['customer_name'],
            'customer_mobile' => $param['customer_mobile'],
            'customer_address' => $param['customer_address']
        );
        $data = zhidahao::makePostParamsUrl($params);
        dump($data);exit;
        $ch = curl_init ();
        $url = zhidahao::ORDER_ADD_PAY_URL;

        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
        $return = curl_exec ( $ch );
        curl_close ( $ch );

        echo $return;
        return $data;

    }

    public function callback(){

    }

    public function notify(){
    }
}