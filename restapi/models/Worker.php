<?php
namespace restapi\models;
use \core\models\worker\WorkerAccessToken;
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
        $msg = array('code'=>0,'msg'=>'','worker_id'=>0);
        if(!isset($param['access_token'])||!$param['access_token']){
           $msg['msg'] = '请您登录';
           return $msg;
        }
        try{
            $isright_token = WorkerAccessToken::checkAccessToken($param['access_token']);
            $worker = WorkerAccessToken::getWorker($param['access_token']);
        }catch (\Exception $e) {
            $msg['code'] = '1024';
            $msg['msg'] = 'boss系统错误';
            return $msg;
        }
        if(!$isright_token){
            $msg['msg'] = '用户认证已经过期,请重新登录';
            return $msg;
        }
        if (!$worker|| !$worker->id) {
            $msg['msg'] = '阿姨不存在';
            return $msg;
        }
        //验证通过
        $msg['code'] = 1;
        $msg['msg'] = '验证通过';
        $msg['worker_id'] = $worker->id;
        return $msg;
    }
}
