<?php

namespace core\models\operation\coupon;

use Yii;
use core\models\customer\Customer;
use core\models\Operation\coupon\Coupon;
use core\models\Operation\coupon\CouponCode;
/**
 * This is the model class for table "{{%coupon_customer}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $order_id
 * @property integer $coupon_id
 * @property integer $coupon_code_id
 * @property string $coupon_code
 * @property string $coupon_name
 * @property string $coupon_price
 * @property integer $expirate_at
 * @property integer $is_used
 * @property integer $created_at
 * @property integer $updated_at
* @property integer $is_del
 */
class CouponCustomer extends \common\models\operation\coupon\CouponCustomer
{

    
    /**
     * list customer's coupon while phone is access
     */
    public static function listCustomerCoupon($phone){
        //check customer is exist 
        $customer =  Customer::find()->where(['customer_phone'=>$phone])->one();
        if($customer == NULL) return false;

        //list coupon
        $couponCustomer = self::find()->where(['customer_id'=>$customer->id])->joinWith(['coupon', 'coupon_code'])
            ->orderBy('coupon_customer.expirate_at asc, coupon.coupon_price desc')->all();      
   
        return $couponCustomer;
   }
    /**
     * 获取用户优惠码
     * @customer_id int    用户id
     * @city_name   string 城市名称
     * @type   int   标示 来区别用户是否调用该城市下面的优惠码
     * @return  array 用户优惠码
     */
    public static function getCouponCustomer($customer_id, $type = '')
    {
        if ($type) {
            $coupon_id = 'coupon_code,coupon_name,coupon_price,coupon_code_id';
        } else {
            $coupon_id = 'coupon_id';
        }
        return CouponCustomer::find()->select($coupon_id)->where(["customer_id" => $customer_id])->asArray()->all();
    }

    
    /**
     * 获取用户优惠码
     * @customer_id int    用户id
     * @return  array 用户优惠码
     */
    public static function CouponCount($customer_id)
    {
       
        return CouponCustomer::find()->where(["customer_id" => $customer_id])->count();
    }

    
}
