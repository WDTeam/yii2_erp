<?php

namespace core\models\worker;

use Yii;
use core\models\worker\WorkerCode;
use core\models\worker\Worker;

/**
 * This is the model class for table "{{%worker_access_token}}".
 *
 * @property integer $id
 * @property string $worker_access_token
 * @property integer $worker_access_token_expiration
 * @property integer $worker_code_id
 * @property string $worker_code
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class WorkerAccessToken extends \dbbase\models\worker\WorkerAccessToken
{
    public static function generateAccessToken($phone, $code){
         $check_code = WorkerCode::checkCode($phone, $code);
        if ($check_code == false) {
            return $check_code;
        }


        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $workerAccessTokens = self::find()->where(['worker_phone'=>$phone])->all();
            foreach ($workerAccessTokens as $workerAccessToken) {
                $workerAccessToken->is_del = 1;
                $workerAccessToken->validate();
                $workerAccessToken->save();
            }
            $workerCode = WorkerCode::find()->where(['worker_phone'=>$phone, 'worker_code'=>$code, 'is_del'=>0])->one();
            $worker_code_id = $workerCode == NULL ? 0 : $workerCode->id;
            $workerAccessToken = new WorkerAccessToken;
            $randstr = '';
            for ($i=0; $i < 4; $i++) { 
                $randstr .= rand(0, 9);
            }
            $workerAccessToken->worker_access_token = md5($phone.$code.$randstr);
            $workerAccessToken->worker_access_token_expiration = 365 * 24 * 3600;
            $workerAccessToken->worker_code_id = $worker_code_id;
            $workerAccessToken->worker_code = $code;
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
        unset($worker['worker_password']);
        unset($worker['worker_idcard']);
        return $worker == NULL ? false : $worker;
    }

}
