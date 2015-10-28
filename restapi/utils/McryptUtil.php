<?php
/**
 * Mcrypy 可逆加密算法工具类
 */
class ScreetUtil
{

	const KEY "1jiajie-boss";
	const EXPIRATION = 2 * 3600;

	/**
	 * Mcrypt加密子程序
	 */
	static public function encode($customer_id)
	{
		// if (function_exists('mcrypt_create_iv')){ 
		// 	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB); 
		// 	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND); 
		// } 

		// if (isset($customer_id)) {
		// 	$access_token = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(KEY), $date, MCRYPT_MODE_ECB, $customer_id);
		// }
		$access_token = time() . md5(KEY) . $customer_id;

		$access_token = base64_encode($access_token);
		return $access_token;
	}

	/**
	 * Mcrypt解密子程序
	 */
	static public function decode($access_token)
	{
		$access_token = base64_decode($access_token);
		$created_at = substr($access_token, 0, 11);
		$key = substr($access_token, 11, 43);
		$customer_id = substr($access_token, 43);

		return $
	}


	

}