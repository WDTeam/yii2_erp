<?php

namespace core\models\customer;

use Yii;

/**
 * This is the model class for table "{{%customer_code}}".
 *
 * @property integer $id
 * @property string $customer_code
 * @property integer $customer_code_expiration
 * @property string $customer_phone
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CustomerCode extends \common\models\CustomerCode
{

    /**
     * 生成验证码并发送
     */
    public static function generateAndSend($phone){
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $customerCodes = CustomerCode::findAll('customer_phone'=>$phone);
            foreach ($customerCodes as $customerCode) {
                $customerCode->is_del = 1;
                $customerCode->validate();
                $customerCode->save();
            }
            $customerCode = new CustomerCode;
            $customer_code = '';
            for ($i=0; $i < 4; $i++) { 
                $customer_code .= rand(0, 9);
            }
            $customerCode->customer_code = $customer_code;
            $custoemrCode->customer_code_expiration = 90;
            $customerCode->customer_phone = $phone;
            $customerCode->created_at = time();
            $customerCode->updated_at = 0;
            $customerCode->is_del = 0;
            $customerCode->validate();
            $customerCode->save();
            $msg = '您本次的验证码为'.$customer_code.', 欢迎使用e家洁APP';
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
        $customerCode = self::find()->where(['customer_phone'=>$phone, 'customer_code'=>$code, 'is_del'=>0])->one();
        if ($customerCode == NULL) {
            return false;
        }
        if ($customerCode->created_at < time() || $customerCode->created_at + $customerCode->customer_code_expiration > time()) {
            return true;
        }
        return false;
    }
}
