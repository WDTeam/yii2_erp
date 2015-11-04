<?php
header ( 'Content-type:text/html;charset=utf-8' );
date_default_timezone_set("PRC");
include_once dirname(__FILE__).'/utf8/func/common.php';
include_once dirname(__FILE__).'/utf8/func/SDKConfig.php';
include_once dirname(__FILE__).'/utf8/func/secureUtil.php';
include_once dirname(__FILE__).'/utf8/func/httpClient.php';
/**
 * 消费交易-控件后台订单推送
 */


/**
 *	以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己需要，按照技术文档编写。该代码仅供参考
 */
class uppay_class{

    public function get($param)
    {
        $params = array(
            'version' => '5.0.0',				//版本号
            'encoding' => 'utf-8',				//编码方式
            'certId' => getSignCertId (),			//证书ID
            'txnType' => '01',				//交易类型
            'txnSubType' => '01',				//交易子类
            'bizType' => '000201',				//业务类型
            'frontUrl' =>  $param['notify_url'],  		//前台通知地址，控件接入的时候不会起作用
            'backUrl' => $param['notify_url'],		//后台通知地址
            'signMethod' => '01',		//签名方法
            'channelType' => '08',		//渠道类型，07-PC，08-手机
            'accessType' => '0',		//接入类型
            'merId' => '898111448161364',	//商户代码，请改自己的测试商户号
            'orderId' => $param['out_trade_no'],	//商户订单号，8-40位数字字母
            'txnTime' => date('YmdHis'),	//订单发送时间
            'txnAmt' => $param['payment_money'],		//交易金额，单位分
            'currencyCode' => '156',	//交易币种
            'orderDesc' => $param['subject'],  //订单描述，可不上送，上送时控件中会显示该信息
            'reqReserved' =>$param['subject'], //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现
        );

        // 签名
        sign ( $params );
        getRequestParamString ( $params );
        // 发送信息到后台
        $result = sendHttpRequest ( $params, SDK_App_Request_Url );
        parse_str($result,$url_param);
        return $url_param;
    }

    public function callback(){
        $status = false;
        if (isset ( $_POST ['signature'] )) {
            $status = _verify ( $_POST ) ? true : false;
        }
        return $status;
    }

    public function notify(){
        echo "验签成功";
    }
}
