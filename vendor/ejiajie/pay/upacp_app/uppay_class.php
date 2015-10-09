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
            'frontUrl' =>  SDK_FRONT_NOTIFY_URL,  		//前台通知地址，控件接入的时候不会起作用
            'backUrl' => SDK_BACK_NOTIFY_URL,		//后台通知地址
            'signMethod' => '01',		//签名方法
            'channelType' => '08',		//渠道类型，07-PC，08-手机
            'accessType' => '0',		//接入类型
            'merId' => '898111448161364',	//商户代码，请改自己的测试商户号
            'orderId' => $param['out_trade_no'],	//商户订单号，8-40位数字字母
            'txnTime' => date('YmdHis'),	//订单发送时间
            'txnAmt' => $param['general_pay_money'],		//交易金额，单位分
            'currencyCode' => '156',	//交易币种
            'orderDesc' => $param['subject'],  //订单描述，可不上送，上送时控件中会显示该信息
            'reqReserved' =>'透传信息', //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现
        );

        // 签名
        sign ( $params );
        getRequestParamString ( $params );
        // 发送信息到后台
        $result = sendHttpRequest ( $params, SDK_App_Request_Url );

        return $result;
    }

    public function callback(){
        if (isset ( $_POST ['signature'] )) {
            $status = verify ( $_POST ) ? true : false;
            //$orderId = $_POST ['orderId']; //其他字段也可用类似方式获取
        } else {
            $status = false;
        }
        return $status;
    }

    public function notify(){
        echo "验签成功";
    }
}

//
//{
//    "version":"5.0.0"
//    "encoding":"utf-8"
//    "txnType":"01"
//    "txnSubType":"01"
//    "bizType":"000201"
//    "frontUrl":"http://101.231.204.84:11006/ACPTest/FrontRcvResponse.do"
//    "backUrl":"http://101.231.204.84:11006/ACPTest/BackRcvResponse.do"
//    "accessType":"0"
//    "merId":"***************"
//    "orderId":"201510082022133261"
//    "txnTime":"20151008202213"
//    "currencyCode":"156"
//    "accType":"01"
//    "txnAmt":"1"
//    "channelType":"07"
//    "signMethod":"01"
//    "signature":"juHxzmwzpUrtycem3Pe62cCnGEWj8AiTX9LhsSqK9GJIwX8a2vlrs2Ded9dB6ppdBZqMs29IBpszK+BUYOUiu1UcVXpG5xtf0a/Fq1hrBe1CvANgO8Ph9iHT62UWaGMfUagUESnqJ4L807CGfhr5Rf00UscNbSFieZSWMQcYR2o="
//    "certId":"124876885185794726986301355951670452718"
//}
//    version=5.0.0
//    encoding=utf-8
//    txnType=01
//    txnSubType=01
//    bizType=000201
//    frontUrl=http%3A%2F%2Flocalhost%3A8085%2Fupacp_sdk_php%2Fdemo%2Futf8%2FFrontReceive.php
//    backUrl=http%3A%2F%2F114.82.43.123%2Fupacp_sdk_php%2Fdemo%2Futf8%2FBackReceive.php
//    accessType=0
//    merId=898111448161364
//    orderId=151008233971
//    txnTime=20151008202149
//    ¤cyCode=156
//    txnAmt=100
//    channelType=08
//    signMethod=01
//    signature=onroGp0wrirLo65TfkoL2Fk0sHRQN2vj77DrL%2BYzRWjT0vwONS9WiLxRioU61B1UyE98GWdjTbSK3vQy1kuXsuKAMTlFd3VduqF2Adg2bugVxEsvoynV3SWlNgF7w9eU%2BP0v324Qm%2BnrUfERYQ7Pc5EnkIJ4b9TQFrGcwfTCwy0BlQrZSxr6SZxCdrQzZ60%2Bh8GPtZ9I0rG%2FIukIEKx1yQ%2BMW3c97qf%2Fto1gDXUurFA%2B4zzje6sC0UNSpyJar%2F6gazPKtgZgjae2HxEynurZ%2BvnofKFvUHrhdORXpK1rFpxJyRnon6ryXDea4qfpPSDI3c5u4%2FYUMA4NyDa8wI%2FVoA%3D%3D
//    certId=69581800278
//
//
//
//
//    //orderDesc=e%E5%AE%B6%E6%B4%81%E4%BC%9A%E5%91%98%E5%85%85%E5%80%BC
//    //reqReserved=%E9%80%8F%E4%BC%A0%E4%BF%A1%E6%81%AF
