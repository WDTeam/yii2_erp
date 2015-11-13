<?php

namespace core\models\customer;

use Yii;
use core\models\customer\CustomerCode;
use core\models\customer\Customer;
use core\models\operation\OperationOrderChannel;
use yii\base\Exception;


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
class CustomerAccessToken extends \dbbase\models\customer\CustomerAccessToken
{
    /**
     * generating access token for rest api users, phone and code must be provieded,
     * if customer is no exist, customer will created but channal_id must be provided
     * @param $phone
     * @param $code
     * @param int $channal_id
     * @return bool|string
     */
    public static function generateAccessToken($phone, $code, $channal_id=0){
        $check_code = CustomerCode::checkCode($phone, $code);
        if ($check_code == false) {
            return $check_code;
        }
        
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            //没有客户则创建
            $customer = Customer::find()->where(['customer_phone'=>$phone])->one();
            if ($customer == NULL) {
                $hasAdded = Customer::addCustomer($phone, $channal_id);
            }

            $customerAccessTokens = self::find()->where(['customer_phone'=>$phone])->all();
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
            $customerAccessToken->customer_access_token_expiration = 365 * 24 * 3600;
            $customerAccessToken->customer_code_id = $customer_code_id;
            $customerAccessToken->customer_code = $code;
            $customerAccessToken->customer_phone = $phone;
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
     * generate access token for apple checker
     * @param $phone
     * @return bool
     */
    public static function generateAccessTokenForAppleChecker($phone){
        $customerAccessToken = new CustomerAccessToken;
        $customerAccessToken->customer_access_token = md5($phone);
        $customerAccessToken->customer_access_token_expiration = 365 * 24 * 3600;
        $customerAccessToken->customer_code_id = 0;
        $customerAccessToken->customer_code = '';
        $customerAccessToken->customer_phone = $phone;
        $customerAccessToken->created_at = time();
        $customerAccessToken->updated_at = 0;
        $customerAccessToken->is_del = 0;
        if(!$customerAccessToken->validate()){
            return false;
        }
        $customerAccessToken->save();
        return true;
    }

    /**
     * check access token
     * @param $access_token
     * @return bool
     */
    public static function checkAccessToken($access_token){
        $customerAccessToken = self::find()->where(['customer_access_token'=>$access_token, 'is_del'=>0])->one();
        if ($customerAccessToken == NULL) {
            return false;
        }else if (($customerAccessToken->created_at <= time()) && ($customerAccessToken->created_at + $customerAccessToken->customer_access_token_expiration >= time())) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * get customer infomation by access token
     * @param $access_token
     * @return array|bool|\dbbase\models\customer\Customer|null
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
        $customer = Customer::find()->where(['customer_phone'=>$customerAccessToken->customer_phone])->one();
        unset($customer["is_del"]);
        return $customer == NULL ? false : $customer;
    }
    
    /****************************access token for pop************************************/
    /**
     * check sign for pop
     * @param $phone
     * @param $sign
     * @param $channal_id
     * @return bool
     */
    public static function checkSign($phone, $sign, $channal_id){

        
        $key = 'pop_to_boss';
		$channal_name = OperationOrderChannel::get_post_name($channal_id);

		if($channal_name == '未知'){
            return false;
        }
        if (md5($phone.$key) != $sign) {
            return false;
        }
        return true;
    }

    /**
     * generating access token for pop users
     * @param $phone
     * @param $sign
     * @param int $channal_id
     * @return array
     */
    public static function generateAccessTokenForPop($phone, $sign, $channal_id=0){
        $check_sign = self::checkSign($phone, $sign, $channal_id);
        if (!$check_sign) {
            return ['response'=>'error', 'errcode'=>1, 'errmsg'=>'验证签名失败'];
        }
        $is_block_arr_info = Customer::isBlockByPhone($phone);
        if($is_block_arr_info['response'] == 'success' && $is_block_arr_info['is_block'] == 1){
            return ['response'=>'error', 'errcode'=>5, 'errmsg'=>'该账号已锁定，不可登陆成功'];
        }
        
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            //没有客户则创建
            $customer = Customer::find()->where(['customer_phone'=>$phone])->one();
            if ($customer == NULL) {
                $hasAdded = Customer::addCustomer($phone, $channal_id);
                if(!$hasAdded){
                    throw new Exception('新增客户注册来源失败');
                }
            }

            $customerAccessTokens = self::find()->where(['customer_phone'=>$phone])->all();
            if (!empty($customerAccessTokens)) {
                foreach ($customerAccessTokens as $customerAccessToken) {
                    $customerAccessToken->is_del = 1;
                    $customerAccessToken->validate();
                    $customerAccessToken->save();
                }
            }
        
            $customerAccessToken = new CustomerAccessToken;
            $randstr = '';
            for ($i=0; $i < 4; $i++) { 
                $randstr .= rand(0, 9);
            }

            $customerAccessToken->customer_access_token = md5($phone.$randstr);
            $customerAccessToken->customer_access_token_expiration = 365 * 24 * 3600;
            $customerAccessToken->customer_code_id = 0;
            $customerAccessToken->customer_code = '';
            $customerAccessToken->customer_phone = $phone;
            $customerAccessToken->created_at = time();
            $customerAccessToken->updated_at = 0;
            $customerAccessToken->is_del = 0;
            $customerAccessToken->validate();
            $customerAccessToken->save();
            $transaction->commit();
            return ['response'=>'success', 'errcode'=>0, 'errmsg'=>'', 'access_token'=>$customerAccessToken->customer_access_token];

        }catch(\Exception $e){
            $transaction->rollback();
            return ['response'=>'error', 'errcode'=>2, 'errmsg'=>'生成access token失败'];
        }
    }
    
    /******************************access token for weixin*******************************/
    /**
     * create weixin customer while weixin id is availible
     * @param $phone
     * @param $code
     * @param $weixin_id
     * @return array
     */
    public static function createWeixinCustomer($phone, $code, $weixin_id){
        $access_token = self::generateAccessToken($phone, $code);
        if(!$access_token){
            return ['response'=>'error', 'errcode'=>'1', 'errmsg'=>'生成access_token 失败'];
        }
        $customer = self::getCustomer($access_token);
        if(!$customer){
            return ['response'=>'error', 'errcode'=>'2', 'errmsg'=>'获取客户信息失败'];
        }
        $customer->customer_is_weixin = 1;
        $customer->weixin_id = $weixin_id;
        $customer->updated_at = time();
        if(!$customer->validate()){
            return ['response'=>'error', 'errcode'=>'3', 'errmsg'=>'创建微信客户失败'];
        }
        $customer->save();
        return ['response'=>'success', 'errcode'=>'0', 'errmsg'=>'', 'access_token'=>$access_token, 'customer'=>$customer];
    }

    /**
     * check sign for weixin
     * @param $weixin_id
     * @param $sign
     * @return array
     */
    public static function checkSignForWeixin($weixin_id, $sign){
        $key = 'weixin_to_boss';
		
        if (md5($weixin_id.$key) != $sign) {
            return ['response'=>'error', 'errcode'=>'1', 'errmsg'=>'check sign failed'];
        }
        return ['response'=>'success', 'errcode'=>'0', 'errmsg'=>''];
    }

    /**
     * generate access token for weixin customer
     * @param $weixin_id
     * @param $sign
     * @return array
     */
    public static function generateAccessTokenForWeixin($weixin_id, $sign){
        $check_sign = self::checkSignForWeixin($weixin_id, $sign);
        if($check_sign['response'] == 'error'){
            return $check_sign;
        }
        
        $customer_arr_info = self::getCustomerByWeixinId($weixin_id);
        if($customer_arr_info['response'] == 'error'){
            return $customer_arr_info;
        }
        
        $is_block_arr_info = Customer::isBlock($customer_arr_info['customer']['id']);
        if($is_block_arr_info['response'] == 'success' && $is_block_arr_info['is_block'] == 1){
            return ['response'=>'error', 'errcode'=>5, 'errmsg'=>'该账号已锁定，不可登陆成功'];
        }
            
        $phone = $customer_arr_info['customer']['customer_phone'];
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $customerAccessTokens = self::find()->where(['customer_phone'=>$phone])->all();
            if (!empty($customerAccessTokens)) {
                foreach ($customerAccessTokens as $customerAccessToken) {
                    $customerAccessToken->is_del = 1;
                    $customerAccessToken->validate();
                    $customerAccessToken->save();
                }
            }
        
            $customerAccessToken = new CustomerAccessToken;
            $randstr = '';
            for ($i=0; $i < 4; $i++) { 
                $randstr .= rand(0, 9);
            }

            $customerAccessToken->customer_access_token = md5($phone.$randstr);
            $customerAccessToken->customer_access_token_expiration = 365 * 24 * 3600;
            $customerAccessToken->customer_code_id = 0;
            $customerAccessToken->customer_code = '';
            $customerAccessToken->customer_phone = $phone;
            $customerAccessToken->created_at = time();
            $customerAccessToken->updated_at = 0;
            $customerAccessToken->is_del = 0;
            $customerAccessToken->validate();
            $customerAccessToken->save();
            $transaction->commit();
            return ['response'=>'success', 'errcode'=>'0', 'errmsg'=>'', 'access_token'=>$customerAccessToken->customer_access_token, 'customer'=>$customer_arr_info['customer']];

        }catch(\Exception $e){
            $transaction->rollback();
            return ['response'=>'error', 'errcode'=>'4', 'errmsg'=>'generate access token failed'];
        }
    }

    /**
     * get customer by weixin_id
     * @param $weixin_id
     * @return array
     */
    public static function getCustomerByWeixinId($weixin_id){
        $customer = Customer::find()->where(['weixin_id'=>$weixin_id])->asArray()->one();
        if(empty($customer)){
            return ['response'=>'error', 'errcode'=>1, 'errmsg'=>'customer is not exist'];
        }
        return ['response'=>'success', 'errcode'=>0, 'errmsg'=>'', 'customer'=>$customer];
    }
}
