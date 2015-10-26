<?php
class alipay_wap_class{

    public function get($param){
        require_once("alipay.config.php");
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "alipay.wap.create.direct.pay.by.user",
            "partner" => trim($alipay_config['partner']),
            "seller_id" => trim($alipay_config['seller_id']),
            "payment_type"	=> 1,
            "notify_url"	=> $param['notify_url'],
            "return_url"	=> $param['return_url'],
            "out_trade_no"	=> $param['out_trade_no'],
            "subject"	=> $param['subject'],
            "total_fee"	=> $param['total_fee'],
            "show_url"	=> $param['show_url'],
            "body"	=> $param['body'],
            "it_b_pay"	=> 60000,
            "extern_token"	=> '',
            "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
        );


        //建立请求
        require_once("lib/alipay_submit.class.php");
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestUrl($parameter);
        echo $html_text;

        return $parameter;
    }
}