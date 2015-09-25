<?php
header ( 'Content-type:text/html;charset=utf-8' );
/**
 * ???? HttpClient??
 * @param unknown_type $params
 * @param unknown_type $url
 * @return mixed
 */
function sendHttpRequest($params, $url) {
	$opts = getRequestParamString ( $params );
	
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_POST, 1 );
	curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false);//?????
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false);//???HOST
	curl_setopt ( $ch, CURLOPT_SSLVERSION, 3);
	curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
			'Content-type:application/x-www-form-urlencoded;charset=UTF-8' 
	) );
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, $opts );
	
	/**
	 * ??cURL ???????????????????????
	 */
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
	
	// ??cURL?????
	$html = curl_exec ( $ch );
	// close cURL resource, and free up system resources
	curl_close ( $ch );
	return $html;
}

/**
 * ????
 *
 * @param unknown_type $params        	
 * @return string
 */
function getRequestParamString($params) {
	$params_str = '';
	foreach ( $params as $key => $value ) {
		$params_str .= ($key . '=' . (!isset ( $value ) ? '' : urlencode( $value )) . '&');
	}
	return substr ( $params_str, 0, strlen ( $params_str ) - 1 );
}