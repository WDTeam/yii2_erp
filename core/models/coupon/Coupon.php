<?php

namespace core\models\coupon;

use Yii;

use core\models\coupon\CouponCode;

/**
 * This is the model class for table "{{%coupon}}".
 *
 * @property integer $id
 * @property string $coupon_name
 * @property string $coupon_price
 * @property integer $coupon_type
 * @property string $coupon_type_name
 * @property integer $coupon_service_type_id
 * @property string $coupon_service_type_name
 * @property integer $coupon_service_id
 * @property string $coupon_service_name
 * @property integer $coupon_city_limit
 * @property integer $coupon_city_id
 * @property string $coupon_city_name
 * @property integer $coupon_customer_type
 * @property string $coupon_customer_type_name
 * @property integer $coupon_time_type
 * @property string $coupon_time_type_name
 * @property integer $coupon_begin_at
 * @property integer $coupon_end_at
 * @property integer $coupon_get_end_at
 * @property integer $coupon_use_end_days
 * @property integer $coupon_promote_type
 * @property string $coupon_promote_type_name
 * @property string $coupon_order_min_price
 * @property integer $coupon_code_num
 * @property integer $coupon_code_max_customer_num
 * @property integer $is_disabled
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 * @property integer $system_user_id
 * @property string $system_user_name
 */
class Coupon extends \common\models\coupon\Coupon
{
	
	/**
	 * get coupon rule service types
	 */
	public static function getServiceTypes(){
		return array(
			0=>'全网优惠券',
			1=>'类别优惠券',
			2=>'商品优惠券',
		);
	}
	
	/**
	 * get coupon rule service
	 */
	public static function getServiceInfo($id){
		$coupon = self::findOne($id);
		if($coupon == NULL){
			return false;		
		}
		return array(
			'coupon_type'=>$coupon->coupon_type,
			'coupon_type_name'=>$coupon->coupon_type_name,
			'coupon_service_type_id'=>$coupon->coupon_service_type_id,
			'coupon_service_type_id'=>$coupon->coupon_service_type_id,
			'coupon_service_id'=>$coupon->coupon_service_id,
			'coupon_service_name'=>$coupon->coupon_service_name,
		);
	}

	/**
	 * get coupon rule customer city types
	 */
	public static function getCityTypes(){
		return array(
			0=>'全部城市',
			1=>'单个城市',
		);
	}


	/**
	 * get coupon rule city 
	 */
	public static function getCityInfo($id){
		$coupon = self::findOne($id);
		if($coupon == NULL){
			return false;		
		}
		return array(
			'coupon_city_limit'=>$coupon->coupon_city_limit,
			'coupon_city_id'=>$coupon->coupon_city_id,
			'coupon_city_name'=>$coupon->coupon_city_name,
		);
	}

	/**
	 * get coupon rule customer types
	 */
	public static function getCustomerTypes(){
		return array(
			0=>'所有用户',
			1=>'新用户',
			2=>'老用户',
			3=>'会员',
			4=>'非会员',
		);
	}

	/**
	 * get coupon rule customer type
	 */
	public static function getCustomerTypeInfo($id){
		$coupon = self::findOne($id);
		if($coupon == NULL){
			return false;		
		}
		return array(
			'coupon_customer_type'=>$coupon->coupon_customer_type,
			'coupon_customer_type_name'=>$coupon->coupon_customer_type_name,
		);
	}
}
