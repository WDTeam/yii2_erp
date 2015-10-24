<?php

namespace core\models\worker;

use Yii;
use \core\models\worker\Worker;

/**
 * This is the model class for table "{{%worker_code}}".
 *
 * @property integer $id
 * @property string $worker_code
 * @property integer $worker_code_expiration
 * @property string $worker_phone
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class WorkerCode extends \common\models\worker\WorkerCode
{

    /**
     * 生成验证码并发送
     */
    public static function generateAndSend($phone){
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $workerCodes = self::find()->where(['worker_phone'=>$phone])->all();
            foreach ($workerCodes as $workerCode) {
                $workerCode->is_del = 1;
                $workerCode->validate();
                $workerCode->save();
            }
            $workerCode = new WorkerCode;
            $worker_code = '';
            for ($i=0; $i < 4; $i++) { 
                $worker_code .= rand(0, 9);
            }
            
            $workerCode->worker_code = $worker_code;
            $workerCode->worker_code_expiration = 90;
            $workerCode->worker_phone = $phone;
            $workerCode->created_at = time();
            $workerCode->updated_at = 0;
            $workerCode->is_del = 0;
            $workerCode->validate();
            if ($workerCode->hasErrors()) {
                var_dump($workerCode->getErrors());
            }
            $workerCode->save();
            $msg = '您本次的验证码为'.$worker_code.', 欢迎使用e家洁APP';
            $string = Yii::$app->sms->send($phone, $msg, 1);
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollback();
            return false;
        }
    }

    /**
     * 检测验证码是否有效
     */
    public static function checkCode($phone, $code){
        $workerCode = self::find()->where(['worker_phone'=>$phone, 'worker_code'=>$code, 'is_del'=>0])->one();
        if ($workerCode == NULL) {
            return false;
        }
        if ($workerCode->created_at < time() && $workerCode->created_at + $workerCode->worker_code_expiration > time()) {
            return true;
        }
        return false;
    }
}
