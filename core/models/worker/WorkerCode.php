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
class WorkerCode extends \dbbase\models\worker\WorkerCode
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
            $workerCode->worker_code_expiration = 1800;
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
    
    /**
     * 检测是否可以发送验证码 验证规则：一人一天只能发送5次，60秒可以发送一次，验证码有效期为30分钟
     * @param $phone 阿姨手机号
     * @return bloon true:可以发送;false:不能发送
     */
    public static function whetherSendCode($phone){
        $now_time=time();
        $worker_code_info= self::find()->where(['worker_phone'=>$phone])->orderBy(['id'=>SORT_DESC])->one();
        $last_send_time=$worker_code_info['created_at'];
        if($last_send_time+60>$now_time){
            return 1;
        }
        $today_begin=strtotime(date('Y-m-d',time()));
        $today_end=$today_begin+3600 * 24;
        $worker_code_num= self::find()->where(['and',"worker_phone=$phone",['>', 'created_at', "$today_begin"],['<', 'created_at', "$today_end"]])->count();
        if($worker_code_num>=5){
            return 2;
        }
        return 3;
    }
}
