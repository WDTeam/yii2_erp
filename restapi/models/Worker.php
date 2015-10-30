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
           return array('code'=>0,'msg'=>'请您登录','workerInfo'=>array('worker_id'=>0,'worker_is_block'=>0));
        }
        try{
            if(!WorkerAccessToken::checkAccessToken($param['access_token'])){
                return array('code'=>0,'msg'=>'用户认证已经过期,请重新登录','workerInfo'=>array('worker_id'=>0,'worker_is_block'=>0));
            }
            $workerObject = WorkerAccessToken::getWorker($param['access_token']);
            return array('code'=>1,'msg'=>'验证通过','workerInfo'=>array('worker_id'=>$workerObject->id,'worker_is_block'=>$workerObject->worker_is_block));
        }catch (\Exception $e) {
            return array('code'=>1024,'msg'=>'boss系统错误','workerInfo'=>array('worker_id'=>0,'worker_is_block'=>0));
        }
    }
}
