<?php
require_once dirname(__FILE__)."/lib/WxPay.Api.php";
require_once dirname(__FILE__)."/example/WxPay.JsApiPay.php";
require_once dirname(__FILE__)."/lib/WxPay.Notify.php";
require_once dirname(__FILE__)."/lib/WxPay.Data.php";

class wxjspay_class extends WxPayNotify{

    public function __construct(){

    }

    public function get($param)
    {
        $input = new WxPayUnifiedOrder();
        $input->SetBody($param['body']);
        $input->SetAttach($param['subject']);
        $input->SetOut_trade_no($param['out_trade_no']);
        $input->SetTotal_fee($param['general_pay_money']);
        $input->SetTime_start($param['time_start']);
        $input->SetTime_expire($param['time_expire']);
        $input->SetGoods_tag($param['subject']);
        $input->SetNotify_url($param['notify_url']);
        $input->SetTrade_type($param['trade_type']);
        $input->SetOpenid($param['openid']);
        $order = WxPayApi::unifiedOrder($input);

        $tools = new JsApiPay();
        $jsApiParameters = $tools->GetJsApiParameters($order);
        return $jsApiParameters;
    }

    /**
     * 回调
     */
    public function callback(){
        $this->Handle(false);
    }

    public function notify(){
        return $this->GetReturn_code();
    }


    public function getNotifyData(){
        return $this->GetValues();
    }



    //查询订单
    public function Queryorder($transaction_id)
    {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);
        Log::DEBUG("query:" . json_encode($result));
        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            return true;
        }
        return false;
    }

    //重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {
        Log::DEBUG("call back:" . json_encode($data));
        $notfiyOutput = array();

        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            return false;
        }
        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"])){
            $msg = "订单查询失败";
            return false;
        }
        return true;
    }

}