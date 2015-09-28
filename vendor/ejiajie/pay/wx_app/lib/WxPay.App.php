<?php
/**
 * Created by PhpStorm.
 * User: ejiajie-453
 * Date: 2015/9/28
 * Time: 10:36
 */
class WxPayApp{

    /**
     * 统一下单
     * https://api.mch.weixin.qq.com/pay/unifiedorder
     */
    public function unifiedorder($param)
    {
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        $timeOut = 6;
        $data = array(
            'appid'=> WxPayConfig::APPID,
            'mch_id'=> WxPayConfig::MCHID,
            'device_info' => 'wxapp',
            'nonce_str' => $this->nonce_str(),
            'body' =>$param['body'],
            'detail' =>$param['body'],
            'out_trade_no' => $param['out_trade_no'],   //订单号
            'fee_type' => 'CNY',
            'total_fee' => $param['general_pay_money'], //订单总金额
            'spbill_create_ip' =>$_SERVER['REMOTE_ADDR'],   //ip
            'time_start' => $param['time_start'],
            'time_expire' => $param['time_expire'],
            'notify_url' =>$param['notify_url'],
            'trade_type' => $param['trade_type'],
        );
        $data['sign'] = $this->makeSign($data);
        $xml = $this->ToXml($data);
        $response = self::postXmlCurl($xml, $url, false, $timeOut);
        var_dump($response);
    }

    /**
     * 随机数算法
     */
    public function nonce_str()
    {
        $time = microtime(true);
        $rand = mt_rand(10000,99999);
        return md5($time.$rand);
    }

    /**
     * 签名算法
     * @return string
     */
    public function makeSign($data)
    {
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

    /**
     * 以post方式提交xml到对应的接口url
     *
     * @param string $xml  需要post的xml数据
     * @param string $url  url
     * @param bool $useCert 是否需要证书，默认不需要
     * @param int $second   url执行超时时间，默认30s
     * @throws WxPayException
     */
    private static function postXmlCurl($xml, $url, $useCert = false, $second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        //如果有配置代理这里就设置代理
        if(WxPayConfig::CURL_PROXY_HOST != "0.0.0.0"
            && WxPayConfig::CURL_PROXY_PORT != 0){
            curl_setopt($ch,CURLOPT_PROXY, WxPayConfig::CURL_PROXY_HOST);
            curl_setopt($ch,CURLOPT_PROXYPORT, WxPayConfig::CURL_PROXY_PORT);
        }
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if($useCert == true){
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            throw new WxPayException("curl出错，错误码:$error");
        }
    }

    /**
     * 输出xml字符
     * @throws WxPayException
     **/
    public function ToXml($data)
    {
        if(!is_array($data) || count($data) <= 0)
        {
            throw new WxPayException("数组数据异常！");
        }

        $xml = "<xml>";
        foreach ($data as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }
}