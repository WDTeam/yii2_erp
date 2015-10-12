<?php
include_once "lib/WxPay.Config.php";
include_once "lib/WxPay.Api.php";
include_once 'lib/WxPay.Notify.php';
include_once 'lib/WxPay.App.php';
include_once 'example/log.php';

class wxpay_class extends WxPayNotify{

    public function __construct(){

    }

    public function get($param){

        $input = new WxPayApp();
        $input->unifiedorder($param);

        exit;
        $input = new WxPayUnifiedOrder();
        $input->SetBody($param['body']);
        $input->SetAttach($param['goods_tag']);
        $input->SetOut_trade_no($param['out_trade_no']);
        $input->SetTotal_fee($param['general_pay_money']);
        $input->SetTime_start($param['time_start']);
        $input->SetTime_expire($param['time_expire']);
        $input->SetGoods_tag($param['goods_tag']);
        $input->SetNotify_url($param['notify_url']);
        $input->SetTrade_type($param['trade_type']);
        $order = WxPayApi::unifiedOrder($input);
        if(empty($order['appid'])){
            $order = $this->get($param);
        }

        $data['appid'] = $order['appid'];
        $data['partnerid'] = $order['mch_id'];
        $data['prepayid'] = $order['prepay_id'];
        $data['package'] = 'Sign=WXPay';
        $data['noncestr'] = $order['nonce_str'];
        $data['timestamp'] = time();
        $sign = $this->mkSign($data);
        $data['sign'] = $sign;

        return $data;
    }

    /**
     * 二次签名
     * 公众账号ID	       appid	    String(32)	是	wx8888888888888888	微信分配的公众账号ID
     * 商户号	           partnerid	String(32)	是	1900000109	微信支付分配的商户号
     * 预支付交易会话ID	   prepayid	    String(32)	是	WX1217752501201407033233368018	微信返回的支付交易会话ID
     * 扩展字段	           package	    String(128)	是	Sign=WXPay	暂填写固定值Sign=WXPay
     * 随机字符串	           noncestr	    String(32)	是	5K8264ILTKCH16CQ2502SI8ZNMTM67VS	随机字符串，不长于32位。推荐随机数生成算法
     * 时间戳	           timestamp	String(10)	是	1412000000	时间戳，请见接口规则-参数规定
     * 签名	               sign	        String(32)	是	C380BEC2BFD727A4B6845133519F3AD6	签名，详见签名生成算法
     */
    private function mkSign($data){
        //签名步骤一：按字典序排序参数
        ksort($data);
        $buff = "";
        foreach ($data as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        $string = $buff. "&key=".WxPayConfig::KEY;
        $key = md5($string);
        return strtoupper($key);
    }


    public function callback(){
        $this->Handle(false);
    }

    public function notify(){
        return $this->GetReturn_code();
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
        $this->notfiyOutput = $data;

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

    public function getNotifyData(){
        return $this->notfiyOutput;
    }
}