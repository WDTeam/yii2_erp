<?php
class zhidahao {
	const SK = 'BTEsDuOJb7pSrHPjIAahi3Ked512cGRC';  //加密KEY
	const SP_NO = 1049; //商户号
    const CAT_ID = 3;
    const PRICE = 25;   //单价
    const BFB_PAY_RESULT_SUCCESS = 1;//支付成功状态
	const ORDER_ADD_PAY_URL = "http://m.baidu.com/lightapp/pay/order/online/add";
	const ORDER_REFUND_URL = "http://m.baidu.com/lightapp/pay/order/refund";

	public static function createOrder($params, $secret_key = self::SK) {
		// 按数组键名 正序排序
		$url = 'http://m.baidu.com/lightapp/pay/order/online/add';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$params['sign'] = self::getSignature($params, $secret_key);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
		$output = curl_exec($ch);
		curl_close($ch);
		return json_decode($output, true);
	}
	
	public static function queryOrder($param){

		$params = array();
		$params['sp_no'] = self::SP_NO;
		$params['order_no'] = $param['order_no'];
		$params['sign'] = self::getSignature($params);
		$url = 'http://m.baidu.com/lightapp/pay/order/info/query'. '?' . http_build_query($params);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$output = curl_exec($ch);
		curl_close($ch);
		return json_decode($output, true);
	}


	/**
	 * 签名生成算法
	 * @param Array params 请求参数数组
	 * @param String secret 签名密钥
	 * @return 签名
	 */
	public static function getSignature($params, $session_secret = self::SK)
	{
		if (is_array($params) && is_string($session_secret)) {
			if (ksort($params)) {
				$string_temp = '';
				foreach ($params as $key => $val) {
					if($key != 'sign'){
						$string_temp .= $key . '=' . $val;
					}
				}
				$string_temp .= $session_secret;
				return md5($string_temp);

			} else {
				return false;
			}
		} else {
			return false;
		}
	}


	public static function makePostParamsUrl($params)
	{
		if (is_array($params)) {
			$sign = self::getSignature($params, self::SK);
			if (is_string($sign)) {
				$arr_temp = array();
				foreach ($params as $key => $val) {
					$arr_temp[$key]= $val;
				}
				$arr_temp['sign'] = $sign;
				$str_url = http_build_query($arr_temp);
				return $str_url;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

}

