<?php
namespace restapi\models;

use core\models\worker\WorkerAccessToken;
/**
 * 阿姨登录验证
 */
class Worker 
{
   /**
     * 公用检测阿姨登录情况
     * @param type $param 
     */
    public static function checkWorkerLogin($param=array()){
        if(!isset($param['access_token'])||!$param['access_token']){
           return array('code'=>401,'msg'=> alertMsgEnum::workerLoginFailed,'workerInfo'=>array('worker_id'=>0,'worker_is_block'=>0,'worker_identity_id'=>0));
        }
        try{
            if(!WorkerAccessToken::checkAccessToken($param['access_token'])){
                return array('code'=>401,'msg'=>alertMsgEnum::workerLoginFailed,'workerInfo'=>array('worker_id'=>0,'worker_is_block'=>0,'worker_identity_id'=>0));
            }
            $workerObject = WorkerAccessToken::getWorker($param['access_token']);
            return array('code'=>1,'msg'=>  alertMsgEnum::workerLoginSuccess,'workerInfo'=>array('worker_id'=>$workerObject->id,'worker_is_block'=>$workerObject->worker_is_block,'worker_identity_id'=>$workerObject->worker_identity_id));
        }catch (\Exception $e) {
            return array('code'=>1024,'msg'=>  alertMsgEnum::workerLoginBossFailed,'workerInfo'=>array('worker_id'=>0,'worker_is_block'=>0,'worker_identity_id'=>0));
        }
    }
}
