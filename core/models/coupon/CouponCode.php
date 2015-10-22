<?php

namespace core\models\coupon;

use Yii;

use core\models\coupon\Coupon;

/**
 * This is the model class for table "{{%coupon_code}}".
 *
 * @property integer $id
 * @property integer $coupon_id
 * @property string $coupon_code
 * @property string $coupon_name
 * @property string $coupon_price
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CouponCode extends \common\models\coupon\CouponCode
{
    /**
     * get coupon code by id
	 */
	public static function getCouponCode($id){
		$coupon = Coupon::findOne($id);
		if($coupon == NULL){
			return false;
		}
		$couponCode = self::find()->where(['coupon_id'=>$id])->all();
		if(empty($couponCode){
			return false;
		}
		return $couponCode;
		
	}

	/**
 	 * generate coupon code 
	 */
	public static function generateCouponCode(){
		$coupon_code['length'] = 8;
		$coupon_code['element_lib'] = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 
			'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 
			'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', );

		$coupon_code_str = '';
		$coupon_code_element = '';
		for ($i = 0; $i < $coupon_code['length']; $i++)
		{
			$coupon_code_element = $coupon_code['element_lib'][rand(0, 62)];
			$coupon_code_str .= $coupon_code_element;
		}
		return $coupon_code_str;
	}
}
