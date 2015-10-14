<?php
/**
 * Created by PhpStorm.
 * User: lsq
 * Date: 15-10-13
 * Time: 下午5:15
 */
require_once ("classes/RequestHandler.class.php");
require_once ("classes/client/ClientResponseHandler.class.php");
require_once ("classes/client/TenpayHttpClient.class.php");

class wxrefund_class
{
    private $config = [];
    public function __construct()
    {
        require_once ("tenpay_config.php");
        $this->config['partner'] = $PARTNER;  /* 商户号 */
        $this->config['key'] = $PARTNER_KEY;  /* 密钥 */
    }


    /**
     * 退款接口
     * @param $param[
     *      out_trade_no 商户订单号
     *      transaction_id   交易流水号
     *      out_refund_no    退款订单号
     *      total_fee    订单总金额(分)
     *      refund_fee   退款金额(分)
     *      op_user_passwd   退款密码(MD5)
     * ]
     * @return array
     */
    public function refund($param){

        /*
        Array
        (
            [input_charset] => UTF-8
            [out_refund_no] => 15101431221
            [out_trade_no] => 15101258091
            [partner] => 1217983401
            [reccv_user_name] =>
            [recv_user_id] =>
            [refund_channel] => 0
            [refund_fee] => 1
            [refund_id] => 1111217983401201510145750187
            [refund_status] => 4
            [retcode] => 0
            [retmsg] =>
            [sign] => 4CA82FD41DABA36985A307C8370E2E5A
            [sign_key_index] => 1
            [sign_type] => MD5
            [transaction_id] => 1217983401381510128537567810
        )
        */

        //https://mch.tenpay.com/refundapi/gateway/refund.xml?
        //op_user_id=1217983401&
        //op_user_passwd=4297f44b13955235245b2497399d7a93&
        //out_refund_no=15101396401&
        //out_trade_no=15101258091&
        //partner=1217983401&
        //refund_fee=1&
        //service_version=1.1&
        //sign=72e00863ba4ef8ad2b4c1aa5c77afd8b&
        //total_fee=1&
        //transaction_id=

        /* 商户号 */
        $partner = $this->config['partner'];
        /* 密钥 */
        $key = $this->config['key'];
        /* 创建支付请求对象 */
        $reqHandler = new RequestHandlerRefund();

        //通信对象
        $httpClient = new TenpayHttpClientRefund();

        //应答对象
        $resHandler = new ClientResponseHandler();

        //-----------------------------
        //设置请求参数
        //-----------------------------
        $reqHandler->init();
        $reqHandler->setKey($key);

        $reqHandler->setGateUrl("https://mch.tenpay.com/refundapi/gateway/refund.xml");
        $reqHandler->setParameter("partner", $partner);

        //out_trade_no和transaction_id至少一个必填，同时存在时transaction_id优先
        if(!empty($param['out_trade_no'])) $reqHandler->setParameter("out_trade_no", $param['out_trade_no']);
        if(!empty($param['transaction_id'])) $reqHandler->setParameter("transaction_id", $param['transaction_id']);
        //必须保证全局唯一，同个退款单号财付通认为是同笔请求
        $reqHandler->setParameter("out_refund_no", $param['out_refund_no']);
        $reqHandler->setParameter("total_fee", $param['total_fee']);
        $reqHandler->setParameter("refund_fee", $param['refund_fee']);
        $reqHandler->setParameter("op_user_id", $partner);
        //操作员密码,MD5处理
        $reqHandler->setParameter("op_user_passwd", $param['op_user_passwd']);
        //接口版本号,取值1.1
        $reqHandler->setParameter("service_version", "1.1");
        $reqHandler->setParameter("input_charset", "UTF-8");


        //-----------------------------
        //设置通信参数
        //-----------------------------
        //设置PEM证书，pfx证书转pem方法：openssl pkcs12 -in 2000000501.pfx  -out 2000000501.pem
        //证书必须放在用户下载不到的目录，避免证书被盗取
        $pemPath = __DIR__.'/1217983401_20140924144357.pem';

        $httpClient->setCertInfo($pemPath, "1217983401");
        //设置CA
        $caPath = __DIR__.'/tenpay_ca_cert.crt';
        $httpClient->setCaInfo($caPath);
        $httpClient->setTimeOut(5);
        //设置请求内容
        $httpClient->setReqContent($reqHandler->getRequestURL());
//        dump($reqHandler->getRequestURL());
        //exit;
        //后台调用
        if($httpClient->call()) {
            //设置结果参数
            $resHandler->setContent($httpClient->getResContent());
            $resHandler->setKey($key);

            //判断签名及结果
            //只有签名正确并且retcode为0才是请求成功
            if($resHandler->isTenpaySign() && $resHandler->getParameter("retcode") == "0" ) {
                //取结果参数做业务处理
                //商户订单号
                $out_trade_no = $resHandler->getParameter("out_trade_no");
                //财付通订单号
                $transaction_id = $resHandler->getParameter("transaction_id");
                //商户退款单号
                $out_refund_no = $resHandler->getParameter("out_refund_no");
                //财付通退款单号
                $refund_id = $resHandler->getParameter("refund_id");
                //退款金额,以分为单位
                $refund_fee = $resHandler->getParameter("refund_fee");
                //退款状态
                $refund_status = $resHandler->getParameter("refund_status");

                $result = $resHandler->getAllParameters();
                $msg = '退款成功';
                $status = 1;
                return ['status'=>$status,'msg'=>$msg,'result'=>$result];

                //echo "OK,refund_status=" . $refund_status . ",out_refund_no=" . $resHandler->getParameter("out_refund_no") . ",refund_fee=" . $resHandler->getParameter("refund_fee") . "<br>";


            } else {
                //错误时，返回结果可能没有签名，记录retcode、retmsg看失败详情。
                $result = "验证签名失败 或 业务错误信息:retcode=" . $resHandler->getParameter("retcode"). ",retmsg=" . $resHandler->getParameter("retmsg");
                $msg = '退款验证签名失败';
                $status = 0;
                return ['status'=>$status,'msg'=>$msg,'result'=>$result];
            }

        } else {
            //后台调用通信失败

            $result = "call err:" . $httpClient->getResponseCode() ."," . $httpClient->getErrInfo();
            $msg = '退款失败';
            $status = 0;
            return ['status'=>$status,'msg'=>$msg,'result'=>$result];
            //有可能因为网络原因，请求已经处理，但未收到应答。
        }
    }

    /**
     * 退款查询
     * @param $param[    至少一个必填，
     *      out_trade_no 商户订单号
     *      transaction_id   交易流水号
     *      out_refund_no    退款订单号
     *      refund_id    退款交易流水号
     * ]
     */
    public function refundQuery($param)
    {
        /* 商户号 */
        $partner = $this->config['partner'];
        /* 密钥 */
        $key = $this->config['key'];

        /* 创建支付请求对象 */
        $reqHandler = new RequestHandlerRefund();

        //通信对象
        $httpClient = new TenpayHttpClientRefund();

        //应答对象
        $resHandler = new ClientResponseHandler();

        //-----------------------------
        //设置请求参数
        //-----------------------------
        $reqHandler->init();
        $reqHandler->setKey($key);

        $reqHandler->setGateUrl("https://gw.tenpay.com/gateway/normalrefundquery.xml");
        $reqHandler->setParameter("partner", $partner);
        $reqHandler->setParameter("input_charset", "UTF-8");
        //out_trade_no和transaction_id、out_refund_no、refund_id至少一个必填，
        //同时存在时以优先级高为准，优先级为：refund_id>out_refund_no>transaction_id>out_trade_no

        if(!empty($param['out_refund_no'])) $reqHandler->setParameter("out_refund_no", $param['out_refund_no']);
        if(!empty($param['refund_id'])) $reqHandler->setParameter("refund_id", $param['refund_id']);
        if(!empty($param['out_trade_no'])) $reqHandler->setParameter("out_trade_no", $param['out_trade_no']);
        if(!empty($param['transaction_id'])) $reqHandler->setParameter("transaction_id", $param['transaction_id']);

        //-----------------------------
        //设置通信参数
        //-----------------------------
        $httpClient->setTimeOut(5);
        //设置请求内容
        $httpClient->setReqContent($reqHandler->getRequestURL());

        //后台调用
        if($httpClient->call()) {
            //设置结果参数
            $resHandler->setContent($httpClient->getResContent());
            $resHandler->setKey($key);

            //判断签名及结果
            //只有签名正确并且retcode为0才是请求成功
            if($resHandler->isTenpaySign() && $resHandler->getParameter("retcode") == "0" ) {
                //取结果参数做业务处理
                //商户订单号
                $out_trade_no = $resHandler->getParameter("out_trade_no");

                //财付通订单号
                $transaction_id = $resHandler->getParameter("transaction_id");

                //金额,以分为单位
                $refund_count = $resHandler->getParameter("refund_count");

                $result = $resHandler->getAllParameters();
                $msg = '查询成功';
                $status = 1;
                return ['status'=>$status,'msg'=>$msg,'result'=>$result];

                echo "退款笔数:" . $refund_count;

                //每笔退款详情
                for($i=0; $i<$refund_count; $i++) {
                    echo "第" . ($i+1) . "笔：" . "refund_state_" . $i . "=" . $resHandler->getParameter("refund_state_".$i) . ",out_refund_no_" . $i . "=" . $resHandler->getParameter("out_refund_no_".$i) . ",refund_fee_" . $i . "=" . $resHandler->getParameter("refund_fee_".$i) . "<br>";;

                }

            } else {
                //错误时，返回结果可能没有签名，记录retcode、retmsg看失败详情。
                $result = "验证签名失败 或 业务错误信息:retcode=" . $resHandler->getParameter("retcode"). ",retmsg=" . $resHandler->getParameter("retmsg");
                $msg = '查询验证签名失败';
                $status = 0;
                return ['status'=>$status,'msg'=>$msg,'result'=>$result];
            }

        } else {
            //后台调用通信失败
            $result = "call err:" . $httpClient->getResponseCode() ."," . $httpClient->getErrInfo();
            $msg = '查询失败';
            $status = 0;
            return ['status'=>$status,'msg'=>$msg,'result'=>$result];
            //有可能因为网络原因，请求已经处理，但未收到应答。
        }
    }
}