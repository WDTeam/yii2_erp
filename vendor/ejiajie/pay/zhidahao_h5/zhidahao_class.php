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
            'goods_name' => $param['goods_name'],
            'return_url' => $param['return_url'],
            'page_url' => $param['page_url'],
            'detail' => json_encode($param['detail']),
            'order_source_url' => $param['order_source_url'],
            'customer_name' => $param['customer_name'],
            'customer_mobile' => $param['customer_mobile'],
            'customer_address' => $param['customer_address']
        );

        return $params;

    }

    public function callback(){

    }

    public function notify(){
    }
}