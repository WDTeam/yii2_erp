<?php

namespace core\models\customer;

use Yii;
use \core\models\customer\Customer;

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
class CustomerCode extends \dbbase\models\customer\CustomerCode
{

    /**
     * 生成验证码并发送
     */
    public static function generateAndSend($phone){
        $transaction = \Yii::$app->db->beginTransaction();
        
        $expirated_arr_info = self::isLastCodeExpirated($phone);
        if($expirated_arr_info['response'] == 'success' && !$expirated_arr_info['is_last_code_expirated']){
            return false;
        }
        
        $send_over_arr_info = self::isSendOver($phone);
        if($send_over_arr_info['response'] == 'success' && $send_over_arr_info['is_send_over']){
            return false;
        }
        
        try{
            //$has_customer = Customer::hasCustomer($phone);
            $customerCodes = self::find()->where(['customer_phone'=>$phone])->all();
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
            $customerCode->customer_code_expiration = self::getCodeConfig('code_send_expiration');
            $customerCode->customer_phone = $phone;
            $customerCode->created_at = time();
            $customerCode->updated_at = 0;
            $customerCode->is_del = 0;
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
        if ($customerCode->created_at <= time() && $customerCode->created_at + $customerCode->customer_code_expiration >= time()) {
            return true;
        }
        return false;
    }
    
/********************************code config******************************************************/
    /**
     * define code config
     */
    public static function codeConfig(){
        return [
            'code_send_expiration'=>60,
            'code_num_per_day'=>100,
        ];
    }
    
    public static function getCodeConfig($config_name){
        $configs = self::codeConfig();
        foreach($configs as $config_key => $config_val){
            if($config_key == $config_name){
                return $config_val;
            }
        }
        return 'not_set';
    }
    
    /**
     * whether customer last code has been send to expiration
     */
    public static function isLastCodeExpirated($phone){
        $customer_code = self::find()->where(['customer_phone'=>$phone, 'is_del'=>0])->orderBy('created_at desc')->asArray()->one();
        if(empty($customer_code)){
            return ['response'=>'success', 'errcode'=>0, 'errmsg'=>'', 'is_last_code_expirated'=>true];
        }
        if($customer_code['created_at'] + $customer_code['customer_code_expiration'] < time()){
            return ['response'=>'success', 'errcode'=>0, 'errmsg'=>'', 'is_last_code_expirated'=>true];
        }else{
            return ['response'=>'success', 'errcode'=>0, 'errmsg'=>'', 'is_last_code_expirated'=>false];
        }
    }
    
    /**
     * whether customer code num has been sended to max defined 
     */
    public static function isSendOver($phone){
        $code_num_per_day = self::getCodeConfig('code_num_per_day');
        if($code_num_per_day == 'not_set'){
            return ['response'=>'success', 'errcode'=>0, 'errmsg'=>'', 'is_send_over'=>false];
        }
        
        $today_begin_at = strtotime(date('Y-m-d', time()));
        $today_end_at = $today_begin_at + 24 * 3600;
        $today_num = self::find()->where(['customer_phone'=>$phone])
                ->andWhere(['>', 'created_at', $today_begin_at])
                ->andWhere(['<', 'created_at', $today_end_at])
                ->count();
        if($today_num >= $code_num_per_day){
            return ['response'=>'success', 'errcode'=>0, 'errmsg'=>'', 'is_send_over'=>true];
        }
        return ['response'=>'success', 'errcode'=>0, 'errmsg'=>'', 'is_send_over'=>false];
    }
}
