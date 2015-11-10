<?php
/**
 * 
 * E家洁第三方接口加密类库
 * @author 李勇
 *
 * 使用范例：
 * //Notify API 或者 Common API：请求百度时加密方法
 * $arrParams = array();
 * //正常请求的参数
 * $arrParams['param_1'] = 'abc';
 * $arrParams['param_2'] = 'def';
 * $objSign = new EjjEncryption('百度•爱生活提供的Api Key', '百度•爱生活提供的Secret Key');
 * $arrParams = $objSign->signature($arrParams);
 * ...
 * curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($arrParams));
 * ...
 *
 * //Push API：接收百度请求时，验证加密的方法
 * //arrParams是接收的HTTP参数
 * $objSign = new EjjEncryption('百度•爱生活提供的Api Key', '百度•爱生活提供的Secret Key');
 * $bolCheck = $objSign->checkSignature($arrParams);
 * if ($bolCheck !== true) {
 *  //Forbidden
 * }
 */
namespace restapi\models;

class EjjEncryption {

    /**
     * 百度•爱生活提供的Api Key
     *
     * @var String
     */
    protected $strApiKey;

    /**
     * 百度•爱生活提供的Secret Key
     *
     * @var String
     */
    protected $strSecretKey;

    /**
     * __construct
     *
     * @param $strApiKey
     * @param $strSecretKey
     */
    public function __construct($strApiKey, $strSecretKey) {
        $this->strApiKey = $strApiKey;
        $this->strSecretKey = $strSecretKey;
    }

    /**
     * 响应百度请求时 需用此方法验证请求正确性
     *
     * @param $arrParams
     * @return bool
     */
    public function checkSignature($arrParams) {
        if (empty($arrParams['nonce'])) {
            return false;
        }
        if (empty($arrParams['sign'])) {
            return false;
        }
        if (empty($arrParams['api_key']) || $arrParams['api_key'] != $this->strApiKey) {
            return false;
        }
        $strOldSign = $arrParams['sign'];
        unset($arrParams['sign']);
        ksort($arrParams);
        //生成Base String
        $strBaseString = self::httpBuildQueryRFC3986($arrParams);
        $strNewSign = base64_encode(hash_hmac('sha1', $strBaseString, $this->strSecretKey, true));
        return $strOldSign == $strNewSign;
    }

    /**
     * 请求百度API时，需用此方法对参数进行加密
     *
     * @param $arrParams
     * @return array
     */
    public function signature($arrParams) {
        $arrParams['api_key'] = $this->strApiKey;
        $arrParams['nonce'] = md5(microtime(true));
        //排序
        ksort($arrParams);
        //生成Base String
        $strBaseString = self::httpBuildQueryRFC3986($arrParams);
        //生成签名
        $arrParams['sign'] = base64_encode(hash_hmac('sha1', $strBaseString, $this->strSecretKey, true));
        return $arrParams;
    }

    /**
     * 将一个Map 进行urlencode 符合RFC3986标准
     *
     * @param $arrParams
     * @return string
     */
    public static function httpBuildQueryRFC3986($arrParams) {
        $arrRet = array();
        foreach ($arrParams as $strKey => $strValue) {
            $arrRet[] = sprintf('%s=%s', $strKey, self::urlEncodeRFC3986($strValue));
        }

        return implode('&', $arrRet);
    }

    /**
     * 将一个字符串转换为RFC3986标准的urlencode
     *
     * @param string $strUrl
     * @return string
     */
    public static function urlEncodeRFC3986($strUrl) {
        return str_replace('%7E', '~', rawurlencode($strUrl));
    }

}
