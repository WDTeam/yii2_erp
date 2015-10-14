<?php
/**
 * Created by PhpStorm.
 * User: lsq
 * Date: 15-10-14
 * Time: 下午6:28
 */
if (!defined("BFB_SDK_ROOT"))
{
    define("BFB_SDK_ROOT", dirname(__FILE__) . DIRECTORY_SEPARATOR);
}
require_once(BFB_SDK_ROOT . 'bfb_sdk.php');
require_once(BFB_SDK_ROOT . 'bfb_refund.cfg.php');
error_reporting(0);

class bfbrefund_class
{
    public function __construct()
    {

    }


    public function refund($param)
    {
        $bfb_sdk = new bfb_sdk();
        /*
        *refund.html页面获取的参数
        */

        $output_type = 1;   //响应数据的格式,默认XML
        $output_charset = 1;    //响应数据的编码,默认GBK
        $return_method= 2;    //后台通知请求模式,1=GET,2=POST
        $cashback_time= date("YmdHis"); //退款请求时间
        $return_url = $param['return_url'];     //服务器异步通知地址
        $sp_refund_no = $param['sp_refund_no'];     //退款订单号
        $order_no = $param['order_no'];     //商户订单号
        $cashback_amount = $param['cashback_amount'];   //退款金额(单位/分)

        /*
         * 字符编码转换，百度钱包默认的编码是GBK，商户网页的编码如果不是，请转码。涉及到中文的字段请参见接口文档
         * 步骤：
         * 1. URL转码
         * 2. 字符编码转码，转成GBK
         *
         * $good_name = iconv("UTF-8", "GBK", urldecode($good_name));
         * $good_desc = iconv("UTF-8", "GBK", urldecode($good_desc));
         *
         */

        // 用于测试的商户请求退款接口的表单参数，具体的表单参数各项的定义和取值参见接口文档
        $params = array (
            'service_code' => sp_conf::BFB_REFUND_INTERFACE_SERVICE_ID,
            'input_charset' => sp_conf::BFB_INTERFACE_ENCODING,
            'sign_method' => sp_conf::SIGN_METHOD_MD5,
            'output_type' => $output_type,
            'output_charset' => $output_charset,
            'return_url' => $return_url,
            'return_method' => $return_method,
            'version' =>  sp_conf::BFB_INTERFACE_VERSION,
            'sp_no' => sp_conf::SP_NO,
            'order_no'=>$order_no,
            'cashback_amount' => $cashback_amount,
            'cashback_time' => $cashback_time,
            'currency' => sp_conf::BFB_INTERFACE_CURRENTCY,
            'sp_refund_no' => $sp_refund_no
        );

        $refund_url = $bfb_sdk->create_baifubao_Refund_url($params, sp_conf::BFB_REFUND_URL);

        if(false === $refund_url){
            $bfb_sdk->log('create the url for baifubao pay interface failed');
        }
        else {
            $bfb_sdk->log(sprintf('create the url for baifubao pay interface success, [URL: %s]', $refund_url));
            echo "<script>window.location=\"" . $refund_url . "\";</script>";
        }

    }



    public function refundQuery()
    {

    }
}