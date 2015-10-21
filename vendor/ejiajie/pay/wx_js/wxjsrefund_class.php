<?php
ini_set('date.timezone','Asia/Shanghai');
require_once dirname(__FILE__)."/lib/WxPay.Api.php";
class wxjsrefund_class extends WxPayNotify{

    public function __construct(){

    }

    /**
     * Array
    (
    [status] => 1
    [msg] => 退款成功
    [result] => Array
    (
    [appid] => wx7558e67c2d61eb8f
    [cash_fee] => 1
    [cash_refund_fee] => 1
    [coupon_refund_count] => 0
    [coupon_refund_fee] => 0
    [mch_id] => 10037310
    [nonce_str] => zs2RB7OYmkZWO20f
    [out_refund_no] => 1510210254691
    [out_trade_no] => 1510200115663
    [refund_channel] => Array
    (
    )

    [refund_fee] => 1
    [refund_id] => 2004390062201510210062657930
    [result_code] => SUCCESS
    [return_code] => SUCCESS
    [return_msg] => OK
    [sign] => 246F4FF5D70F846A6F1CE293E970C8A5
    [total_fee] => 1
    [transaction_id] => 1004390062201510201270399816
    )

    )
     * 退款
     * @param $param
     * @return array
     * @throws WxPayException
     */
    public function refund($param)
    {
        $input = new WxPayRefund();
        if($param['transaction_id']){
            $input->SetTransaction_id($param["transaction_id"]);   //退款交易流水号
        }elseif($param['out_trade_no']){
            $input->SetOut_trade_no($param["out_trade_no"]);   //退款订单号
        }
        $input->SetTotal_fee($param["total_fee"]);   //退款总金额
        $input->SetRefund_fee($param["refund_fee"]);   //退款金额
        $input->SetOut_refund_no($param["trade_no"]);   //退款订单ID
        $input->SetOp_user_id(WxPayConfig::MCHID);
        $result = WxPayApi::refund($input);
        if($result['return_code'] == 'SUCCESS'){
            return ['status'=>1,'msg'=>'退款成功','result'=>$result];
        }else{
            return ['status'=>0,'msg'=>'退款失败','result'=>$result];
        }
    }

    /**
     *  查询退款
     */
    public function refundQuery($param)
    {
        $input = new WxPayRefundQuery();
        if( !empty($param["transaction_id"]) ){
            $input->SetTransaction_id($param["transaction_id"]);
        }elseif( !empty($param["out_trade_no"]) ){
            $input->SetOut_trade_no($param["out_trade_no"]);
        }elseif( !empty($param["out_refund_no"]) ){
            $input->SetOut_refund_no($param["out_refund_no"]);
        }elseif( !empty($param["out_refund_no"]) ){
            $input->SetRefund_id($param["out_refund_no"]);
        }

        $result = WxPayApi::refundQuery($input);
        if($result['return_code'] == 'SUCCESS'){
            return ['status'=>1,'msg'=>'退款查询成功','result'=>$result];
        }else{
            return ['status'=>0,'msg'=>'退款查询失败','result'=>$result];
        }

    }





}