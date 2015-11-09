<?php
namespace dbbase\models\system;

class BiLog extends \yii\db\ActiveRecord
{
	
	public static function commitLog($logData){
		
		$url ="";
		
		$ch = curl_init();
		$header = array(
			'Content-Type:application/x-www-form-urlencoded',
		);
		$data = json_encode($logData);
		curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
		// 添加参数
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// 执行HTTP请求
		curl_setopt($ch , CURLOPT_URL , $url);
		$res = curl_exec($ch);
		return $res;
	   
	}
}
