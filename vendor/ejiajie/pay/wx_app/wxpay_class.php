<?php
//header('Content-type: text/json');
//header('Content-type: text/html; charset=gb2312');
//---------------------------------------------------------
//微信支付服务器签名支付请求示例，商户按照此文档进行开发即可
//---------------------------------------------------------

require_once ("classes/RequestHandler.class.php");

require_once ("classes/ResponseHandler.class.php");

require_once ("classes/client/TenpayHttpClient.class.php");


class wxpay_class
{
    public function __construct(){

    }

    /**
     * 获取支付参数
     * @param $param
     * @return mixed
     */
    public function get($param){
        require_once ("tenpay_config.php");
        //获取token值
        $reqHandler = new RequestHandler();
        $reqHandler->init($APP_ID, $APP_SECRET, $PARTNER_KEY, $APP_KEY);
        $Token= $reqHandler->GetToken();

        if ( $Token !='' ){
            //=========================
            //生成预支付单
            //=========================
            //设置packet支付参数
            $packageParams =array();

            $packageParams['bank_type']		= $param['trade_type'];	            //支付类型
            $packageParams['body']			= $param['body'];					//商品描述
            $packageParams['fee_type']		= '1';				//银行币种
            $packageParams['input_charset']	= 'UTF-8';		    //字符集
            $packageParams['notify_url']	= $param['notify_url'];	    //通知地址
            $packageParams['out_trade_no']	= $param['out_trade_no'];		        //商户订单号
            $packageParams['partner']		= $PARTNER;		        //设置商户号
            $packageParams['total_fee']		= $param['payment_money'];			//商品总金额,以分为单位
            $packageParams['spbill_create_ip']= $_SERVER['REMOTE_ADDR'];  //支付机器IP
            //获取package包
            $package= $reqHandler->genPackage($packageParams);

            $time_stamp = time();
            $nonce_str = md5(rand());
            //设置支付参数
            $signParams =array();
            $signParams['appid']	=$APP_ID;
            $signParams['appkey']	=$APP_KEY;
            $signParams['noncestr']	=$nonce_str;
            $signParams['package']	=$package;
            $signParams['timestamp']=$time_stamp;
            $signParams['traceid']	= 'mytraceid_001';
            //生成支付签名
            $sign = $reqHandler->createSHA1Sign($signParams);

            //增加非参与签名的额外参数
            $signParams['sign_method']		='sha1';
            $signParams['app_signature']	=$sign;
            //剔除appkey
            unset($signParams['appkey']);
            //获取prepayid
            $prepayid=$reqHandler->sendPrepay($signParams);

            if ($prepayid != null) {
                $pack	= 'Sign=WXPay';
                //输出参数列表
                $prePayParams =array();
                $prePayParams['appid']		=$APP_ID;
                $prePayParams['appkey']		=$APP_KEY;
                $prePayParams['noncestr']	=$nonce_str;
                $prePayParams['package']	=$pack;
                $prePayParams['partnerid']	=$PARTNER;
                $prePayParams['prepayid']	=$prepayid;
                $prePayParams['timestamp']	=$time_stamp;
                //生成签名
                $sign=$reqHandler->createSHA1Sign($prePayParams);

                $outparams['retcode']=0;
                $outparams['retmsg']='ok';
                $outparams['appid']=$APP_ID;
                $outparams['noncestr']=$nonce_str;
                $outparams['package']=$pack;
                $outparams['prepayid']=$prepayid;
                $outparams['timestamp']=$time_stamp;
                $outparams['sign']=$sign;

            }else{
                $outparams['retcode']=-2;
                $outparams['retmsg']='错误：获取prepayId失败';
            }
        }else{
            $outparams['retcode']=-1;
            $outparams['retmsg']='错误：获取不到Token';
        }

        $outparams['partnerid'] = $PARTNER;
        return $outparams;
    }

    /**
     * 回调验证支付签名
     * @return bool
     */
    public function callback(){
        require_once ("tenpay_config.php");
        /* 创建支付应答对象 */
        $resHandler = new ResponseHandler();
        $resHandler->setKey($PARTNER_KEY);

        //判断签名
        if($resHandler->isTenpaySign() == true) {
            //商户在收到后台通知后根据通知ID向财付通发起验证确认，采用后台系统调用交互模式
            //$data['notify_id'] = $resHandler->getParameter("notify_id");//通知id

            //商户交易单号
            //$data['out_trade_no'] = $resHandler->getParameter("out_trade_no");

            //财付通订单号
            //$data['transaction_id'] = $resHandler->getParameter("transaction_id");

            //商品金额,以分为单位
            //$data['total_fee'] = $resHandler->getParameter("total_fee");

            //如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
            //$data['discount'] = $resHandler->getParameter("discount");

            //支付结果
            $trade_state = $resHandler->getParameter("trade_state");
            //可获取的其他参数还有
            //bank_type			银行类型,默认：BL
            //fee_type			现金支付币种,目前只支持人民币,默认值是1-人民币
            //input_charset		字符编码,取值：GBK、UTF-8，默认：GBK。
            //partner			商户号,由财付通统一分配的10位正整数(120XXXXXXX)号
            //product_fee		物品费用，单位分。如果有值，必须保证transport_fee + product_fee=total_fee
            //sign_type			签名类型，取值：MD5、RSA，默认：MD5
            //time_end			支付完成时间
            //transport_fee		物流费用，单位分，默认0。如果有值，必须保证transport_fee +  product_fee = total_fee

            //判断签名及结果
            if ("0" == $trade_state){
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * 回调处理
     */
    public function notify(){
        //回复服务器处理成功
        echo "Success";
    }

    /**
     * 查询支付结果
     */
    public function orderQuery($out_trade_no)
    {
        require_once ("tenpay_config.php");
        $reqHandler = new RequestHandler();
        $reqHandler->init($APP_ID, $APP_SECRET, $PARTNER_KEY, $APP_KEY);
        $Token= $reqHandler->GetToken();

        //$out_trade_no = '15101258091';
        $data = $reqHandler->orderQuery($Token,$out_trade_no,$PARTNER);
        return $data;

    }
}
