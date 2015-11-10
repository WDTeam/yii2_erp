<?php

namespace restapi\controllers;

use core\models\system\BiLog;


class BiLogController extends \restapi\components\Controller
{
	public function actionTrackPage(){
		$logSourceName ='ejiajie_page_view';
		$log['type-name'] = 'pv_count';
		$log['log-type']='json';

		
		$hostInfo =$_SERVER['HTTP_REFERER'];
		$hostArr = parse_url($hostInfo);
		
		$log['log-data'] = [
			'host_name' =>$hostArr['host'],	
			'request_uri'=>$hostArr['path'],
			'remote_addr'=>$_SERVER['REMOTE_ADDR'],
			'user_agent'=>$_SERVER['HTTP_USER_AGENT'],
			'request_time'=>date("Y-m-d H:i:s",$_SERVER['REQUEST_TIME']),	
		];
		$log['timestamp'] =date("Y-m-d H:i:s",time());
		$logData[$logSourceName] = $log;
		BiLog::commitLog($logData);
		
	}
}