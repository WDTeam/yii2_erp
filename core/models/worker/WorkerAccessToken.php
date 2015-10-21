<?php

namespace core\models\worker;

use Yii;
use core\models\worker\Worker;
/**
 * This is the model class for table "{{%worker_access_token}}".
 *
 * @property integer $id
 * @property string $worker_access_token
 * @property integer $worker_access_token_expiration
 * @property string $worker_password
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class WorkerAccessToken extends \common\models\WorkerAccessToken
{
    public static function generateAccessToken($phone, $password){
        $check_code = Worker::checkWorkerPassword($phone, $password);
        if ($check_code['result'] ==0) {
            return false;
        }

        $transaction = \Yii::$app->db->beginTransaction();
        try{
            //没有阿姨则创建
            $worker = Worker::find()->where(['worker_phone'=>$phone])->one();
            if ($worker == NULL) {
                $worker = new Worker;
                $worker->worker_phone = $phone;
                $worker->created_at = time();
                $worker->updated_at = 0;
                $worker->is_del = 0;
                $worker->save();
            }
            $workerAccessTokens = self::find()->where(['worker_phone'=>$phone])->all();
            foreach ($workerAccessTokens as $workerAccessToken) {
                $workerAccessToken->is_del = 1;
                $workerAccessToken->validate();
                $workerAccessToken->save();
            }
            $workerCode = Worker::find()->where(['worker_phone'=>$phone,  'isdel'=>0])->one();
            $worker_code_id = $workerCode == NULL ? 0 : $workerCode->id;
            $workerAccessToken = new WorkerAccessToken;
            $randstr = '';
            for ($i=0; $i < 4; $i++) { 
                $randstr .= rand(0, 9);
            }
            $workerAccessToken->worker_access_token = md5($phone.$password.$randstr);
            $workerAccessToken->worker_access_token_expiration = 365 * 24 * 3600;
            $workerAccessToken->worker_password = $password;
            $workerAccessToken->worker_phone = $phone;
            $workerAccessToken->created_at = time();
            $workerAccessToken->updated_at = 0;
            $workerAccessToken->is_del = 0;
            $workerAccessToken->validate();
            $workerAccessToken->save();
            $transaction->commit();
            return $workerAccessToken->worker_access_token;

        }catch(\Exception $e){
            $transaction->rollback();
            return false;
        }
    }
     /**
     * 检测access_token是否有效
     */
    public static function checkAccessToken($access_token){
        $workerAccessToken = self::find()->where(['worker_access_token'=>$access_token, 'is_del'=>0])->one();
        if ($workerAccessToken == NULL) {
            return false;
        }else if (($workerAccessToken->created_at <= time()) && ($workerAccessToken->created_at + $workerAccessToken->worker_access_token_expiration >= time())) {
            return true;
        }else{
            return false;
        }
    }
    /**
     * 根据access_token获取客户信息
     */
    public static function getWorker($access_token){
        $able = self::checkAccessToken($access_token);
        if (!$able) {
            return false;
        }
        $workerAccessToken = self::find()->where(['worker_access_token'=>$access_token, 'is_del'=>0])->one();
        if ($workerAccessToken == NULL) {
            return false;
        }

        $worker = Worker::find()->where(['worker_phone'=>$workerAccessToken->worker_phone])->one();
        return $worker == NULL ? false : $worker;
    }
    
}
