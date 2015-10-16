<?php
class zhidahao {
	const SK = 'BTEsDuOJb7pSrHPjIAahi3Ked512cGRC';  //加密KEY
	const SP_NO = 1049; //商户号
    const CAT_ID = 3;
    const PRICE = 25;   //单价
    const BFB_PAY_RESULT_SUCCESS = 1;//支付成功状态
	/**
	 * 签名生成算法
	 * @param Array params 请求参数数组
	 * @param String secret 签名密钥
	 * @return 签名
	*/
	public static function getSignature($params, $secret_key = self::SK) {
		// 按数组键名 正序排序
		ksort($params);
		$tem = array();
		foreach ($params as $k => $v) {
			if ($k !== 'sign') {
				$tem[] = "$k=$v";
			}
		}

		$sk = implode('', $tem) . $secret_key;
		return md5($sk);
	}

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
	
	public static function queryOrder($server_type, $order_id){
		$params = array();
		$params['sp_no'] = self::SP_NO;
		//$params['sp_no'] = 1049;
		$params['order_no'] = getZhidahaoOrderNo($server_type, $order_id);
		//$params['order_id'] = '5166548';
		$params['sign'] = self::getSignature($params);
		$url = 'http://m.baidu.com/lightapp/pay/order/info/query'. '?' . http_build_query($params);
		//echo $url;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$output = curl_exec($ch);
		curl_close($ch);
		return json_decode($output, true);
	}
}
	
function baiduLog($logfile, $data){	
	file_put_contents($logfile, date('[Y-m-d H:i:s]') . $data . "\n", FILE_APPEND);
}

//获取优惠劵价值
function getActiveCodeRmb($code) {
	$testStr = '';
	if ($_SERVER['SERVER_NAME'] != 'm.1jiajie.com') {
		$testStr = 'test.';
	}
	$url = 'http://' . $testStr . 'web.1jiajie.com/v2/active_code.php?active_code=' . $code;
	$return = file_get_contents($url);
	$result = json_decode($return, true);
	if ($result['code']=='ok' && isset($result['msg']['activeList'][0]['promo_code_value'])) {
		return $result['msg']['activeList'][0]['promo_code_value'];
	}
	return 0;
}

function isBaiduServiceZone($city_name){
	return $_COOKIE['from']=='baidu_service_zone' && in_array($city_name, array('北京', '上海'));
}

//组装商户订单号
function getZhidahaoOrderNo($server_type, $order_id){
	$pre = '';
	if ($_SERVER['SERVER_NAME'] != 'm.1jiajie.com') {
		$pre = 'test_';
	}
	return $pre . 'EJIAJIE_' . $server_type . '_' . $order_id;
}	
	
function GetServiceConfByName($serverName = '', $confPath = 'http://web.1jiajie.com/v2/all_service.json?platform_version=wap3.0') {
	$cnfArr = array();

	if (empty($serverName)) {
		return $cnfArr;
	}

	if (!is_file($confPath)) {
	   // return $cnfArr;
	}

	$cnfRawCnt = file_get_contents($confPath);
	$cnfJson = json_decode($cnfRawCnt, true);
	//baiduLog('/tmp/baidu_bihuan_debug.log', $confPath . $cnfRawCnt);
	if ($cnfJson == NULL) {
		return $cnfArr;
	}
	

	foreach ($cnfJson['msg']['items'] as $oneMain => $secondArr) {
		foreach ($secondArr as $oneSecond) {
			$name = $oneSecond['name'];
			if ($name == $serverName) {
				$oneSecond['service_main'] = $oneMain;
				$cnfArr = $oneSecond;
				break;
			}
		}
	}

	return $cnfArr;
}