<?php

namespace core\models\customer;

use Yii;
use core\models\customer\CustomerCode;

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
        $customerCode = CustomerCode::find()->where(['customer_phone'=>$phone, 'customer_code'=>$code, 'is_del'=>0])->one();
        $customer_code_id = $customerCode == NULL ? 0 : $customerCode->id;
        $customerAccessToken = new CustomerAccessToken;
        $customerAccessToken->customer_access_token = md5($phone.$code);
        $customerAccessToken->customer_access_token_expiration = 2 * 3600;
        $customerAccessToken->customer_code_id = $customer_code_id;
        $customerAccessToken->customer_code = $code;
        $customerAccessToken->created_at = time();
        $customerAccessToken->updated_at = 0;
        $customerAccessToken->is_del = 0;
        $customerAccessToken->validate();
        if ($customerAccessToken->hasErrors()) {
            return false;
        }
        $customerAccessToken->save();
        return $customerAccessToken->customer_access_token;
    }
}
