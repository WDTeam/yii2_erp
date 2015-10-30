<?php

namespace core\models\operation\coupon;

use Yii;
use core\models\customer\Customer;
use core\models\operation\coupon\Coupon;
use core\models\operation\coupon\CouponCode;
use yii\behaviors\TimestampBehavior;
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
class CouponCustomer extends \dbbase\models\operation\coupon\CouponCustomer
{
    /**
     * 自动处理创建、修改时间
     * @author CoLee
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
        ];
    }

    /**
     * relation coupon code
     */
    public function getCouponCode(){
       return $this->hasMany(CouponCode::className(), ['id'=>'coupon_code_id'])->onCondition([]);
    }
    /**
     * relation coupon
     */
    public function getCoupon(){
        return $this->hasMany(Coupon::className(), ['id'=>'coupon_id'])->onCondition([]);
    }

    /**
     * list customer's coupon while phone is access
     */
    public static function listCustomerCoupon($phone){
        //check customer is exist 
        $customer =  Customer::find()->where(['customer_phone'=>$phone])->one();
        if($customer == NULL) return false;

        //list coupon
        $couponCustomer = self::find()->where(['customer_id'=>$customer->id])
            ->joinWith('coupon')
            ->joinWith('couponCode')
            ->orderBy('expirate_at asc, coupon_price desc')
            ->all();      
                    
   
        return $couponCustomer;
   }
     /**
     * 获取用户优惠券列表（列表包括下单所在城市的和所有城市都通用的券）
     */
    public static function GetCustomerCouponList($customer_id,$city_id){
        $now_time=time();
        $couponCustomer=(new \yii\db\Query())->select('*')->from('ejj_coupon_customer')
                ->leftJoin('ejj_coupon', 'ejj_coupon_customer.coupon_id = ejj_coupon.id')
                ->where(['and',"ejj_coupon_customer.expirate_at>$now_time",'ejj_coupon_customer.is_del=0','ejj_coupon_customer.is_used=0',"ejj_coupon_customer.customer_id=$customer_id", ['or', ['and','ejj_coupon.coupon_city_limit=1',"ejj_coupon.coupon_city_id=$city_id"], 'ejj_coupon.coupon_city_limit=0']] )
                ->orderBy(['ejj_coupon_customer.expirate_at' => SORT_ASC,'ejj_coupon_customer.coupon_price' => SORT_DESC])->all();
        return $couponCustomer;
   }
    /**
     * 获取用户全部优惠券列表（包括可用的、不可用的、所有城市的、通用的）
     */
    public static function GetAllCustomerCouponList($customer_id,$city_id){
        $couponCustomer = self::find()->where(['customer_id'=>$customer_id])
            ->joinWith('coupon')
            ->orderBy('expirate_at asc, coupon_price desc')
            ->all();       
        return $couponCustomer;
   }
       
    /**
     * 获取用户优惠码数量
     * @customer_id int    用户id
     * @return  array 用户优惠码
     */
    public static function CouponCount($customer_id)
    {
       
        return CouponCustomer::find()->where(["customer_id" => $customer_id])->count();
    }

    
}
