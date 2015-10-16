<?php

namespace core\models\customer;

use Yii;
use core\models\customer\CustomerCode;
use common\models\Customer;

/**
 * This is the model class for table "{{%customer_access_token}}".
 *
 * @property integer $id
 * @property string $customer_access_token
 * @property integer $customer_access_token_expiration
 * @property integer $customer_code_id
 * @property string $customer_code
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CustomerAccessToken extends \common\models\CustomerAccessToken
{
    public static function generateAccessToken($phone, $code){
        if (!CustomerCode::checkCode($phone, $code)) {
            return false;
        }

        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $customerAccessTokens = self::find()->where(['customer_code'=>$code])->all();
            foreach ($customerAccessTokens as $customerAccessToken) {
                $customerAccessToken->is_del = 1;
                $customerAccessToken->validate();
                $customerAccessToken->save();
            }

            $customerCode = CustomerCode::find()->where(['customer_phone'=>$phone, 'customer_code'=>$code, 'is_del'=>0])->one();
            $customer_code_id = $customerCode == NULL ? 0 : $customerCode->id;
            $customerAccessToken = new CustomerAccessToken;
            $randstr = '';
            for ($i=0; $i < 4; $i++) { 
                $randstr .= rand(0, 9);
            }
            $customerAccessToken->customer_access_token = md5($phone.$code.$randstr);
            $customerAccessToken->customer_access_token_expiration = 2 * 3600;
            $customerAccessToken->customer_code_id = $customer_code_id;
            $customerAccessToken->customer_code = $code;
            $customerAccessToken->created_at = time();
            $customerAccessToken->updated_at = 0;
            $customerAccessToken->is_del = 0;
            $customerAccessToken->validate();
            $customerAccessToken->save();
            $transaction->commit();
            return $customerAccessToken->customer_access_token;
            
        }catch(\Exception $e){
            $transaction->rollback();
            return false;
        }
    }

    /**
     * 检测access_token是否有效
     */
    public static function checkAccessToken($access_token){
        $customerAccessToken = self::find()->where(['customer_access_token'=>$access_token, 'is_del'=>0])->one();
        if ($customerAccessToken == NULL) {
            return false;
        }
        if ($customerAccessToken->created_at < time() && $customerAccessToken->created_at + $customerAccessToken->customer_access_token_expiration > time()) {
            return true;
        }
        return false;
    }

    /**
     * 根据access_token获取客户信息
     */
    public static function getCustomer($access_token){
        $able = self::checkAccessToken($access_token);
        if (!$able) {
            return false;
        }
        $customerAccessToken = self::find()->where(['customer_access_token'=>$access_token, 'is_del'=>0])->one();
        if ($customerAccessToken == NULL) {
            return false;
        }
        $customerCode = CustomerCode::findOne($customerAccessToken->customer_code_id);
        if ($customerCode == NULL) {
            return false;
        }
        // var_dump($customerCode);exit();
        $customer = Customer::find()->where(['customer_phone'=>$customerCode->customer_phone])->one();
        return $customer == NULL ? false : $customer;
    }
}
