<?php
namespace restapi\models;

use \core\models\customer\CustomerAccessToken;
use \core\models\operation\coupon\CouponCustomer;
/**
 * api用户登录验证公共方法
 */
class LoginCustomer
{
    /**
     * 公用检测客户登录情况
     * @param type $param 
     */
    public static function checkCustomerLogin($param=array()){
        $msg = array('code'=>0,'msg'=>'','customer_id'=>0);
        if(!isset($param['access_token'])||!$param['access_token']){
           $msg['msg'] = '请登录';
           return $msg;
        }
        try{
            $isright_token = CustomerAccessToken::checkAccessToken($param['access_token']);
            $customer = CustomerAccessToken::getCustomer($param['access_token']);
        }catch (\Exception $e) {
            $msg['code'] = '1024';
            $msg['msg'] = 'boss系统错误';
            return $msg;
        }
        if(!$isright_token){
            $msg['msg'] = '用户认证已经过期,请重新登录';
            return $msg;
        }
        if (!$customer|| !$customer->id) {
            $msg['msg'] = '用户不存在';
            return $msg;
        }
        //验证通过
        $msg['code'] = 401;
        $msg['msg'] = '验证通过';
        $msg['customer_id'] = $customer->id;
        $msg['customer_phone'] = $customer->customer_phone;
        return $msg;
    }
}
