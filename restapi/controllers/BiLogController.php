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
	
	public function actionCommit(){
		$params = Yii::$app->request->post() or
                $params = json_decode(Yii::$app->request->rawBody, true);

        if (empty($params['access_token']) || !CustomerAccessToken::checkAccessToken($params['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 401, 403, null, alertMsgEnum::balancePayFailed);
        }
		$logNum =count($params['log_content']);
		if($logNum>=20){
			return $this->send(null,'日志批量提交数量超过20条了',401,200);
		}
		foreach($params['log_content'] as $key => $value){
			$logArr[]=[
				'type-name'	=> $value['type_name'],
				'log-type' 	=> $value['log-type'],
				'log-data' 	=> $value['log_data'],
				'timestamp' => $value['timestamp'],
			];
		}
		$logData[$params['log_source_name']] = $logArr;
		
			
		
		$res = BiLog::commitLog($logData);
		if($res){
			$res = json_decode($res,true);
			if($res['result']==0){
				return $this->send(null,'操作成功',1,200);
			}else{
				return $this->send(null,$res['msg'],401,200);
			}
		}else{
			return $this->send(null,'操作失败',401,200);
		}
	}
}