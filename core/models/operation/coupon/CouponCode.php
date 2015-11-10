<?php

namespace core\models\operation\coupon;

use Yii;

use core\models\operation\coupon\Coupon;
use core\models\customer\Customer;
use core\models\operation\coupon\CouponCustomer;
use yii\base\InvalidParamException;

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
class CouponCode extends \dbbase\models\operation\coupon\CouponCode
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
		if(empty($couponCode)){
			return false;
		}
		return $couponCode;
		
	}

	/**
 	 * erate coupon code 
	 */
	public static function generateCouponCode(){
		$coupon_code['length'] = 8;
		$coupon_code['element_lib'] = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 
			'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 
			//'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 
		);

		$coupon_code_str = '';
		$coupon_code_element = '';
		for ($i = 0; $i < $coupon_code['length']; $i++)
		{
			$coupon_code_element = $coupon_code['element_lib'][rand(0, 36)];
			$coupon_code_str .= $coupon_code_element;
		}
		return $coupon_code_str;
	}

    /**
     * check coupon code is usable
     */
    public static function checkCouponCode($code){
        $couponCode = self::find()->where(['coupon_code'=>$code, 'is_del'=>0])->one();
        if($couponCode == NULL){
            return false;
        }
        $coupon_id = $couponCode->coupon_id;
        $coupon = Coupon::find()->where('id=:id and is_disable=0 and coupon_begin_at < time()')->one();
        if($coupon == NULL){
            return false;
        }
        return true;
        
    }
    /**
     * 验证活动码是否可用 
     */
    public static function checkCouponCodeIsAble($code){
        $couponCode = self::find()->where(['coupon_code'=>$code, 'is_del'=>0])->one();
        if($couponCode == NULL){
            return false;
        }
        $coupon_id = $couponCode->coupon_id;
        //根据活动码的活动码有效时间类型（coupon_time_type）获得不同的活动码结束时间
        $coupon_time_type=Coupon::couponTimeType($coupon_id);
        if($coupon_time_type['coupon_time_type']==0){
            //领取时间和使用时间一致时的结束时间
            $coupon = Coupon::find()->where('id=:coupon_id and is_disabled=0 and is_del=0 and coupon_begin_at < '.time().' and coupon_end_at > '.time())->addParams([':coupon_id' => $coupon_id])->one();
            if($coupon == NULL){
                return false;
            }else{
                return true;
            }
            
        }elseif($coupon_time_type['coupon_time_type']==1){
            //领取时间和使用时间不一致时的领取结束时间
            $coupon = Coupon::find()->where('id=:coupon_id and is_disabled=0 and is_del=0 and coupon_begin_at < '.time().' and coupon_get_end_at > '.time())->addParams([':coupon_id' => $coupon_id])->one();
            if($coupon == NULL){
                return false;
            }else{
                return true;
            }
        }
        
    }
    /**
     * erate coupon for customer by code
     */
    public static function erateCouponByCode($phone, $code){
        //check customer exists
   
        $customer = Customer::find()->where(['customer_phone'=>$phone])->one();
        //如果用户不存在就创建用户(逻辑未定先返回false)
        if($customer == NULL){
            return false;
        }
        //检查优惠码是否已经被兑换
        $couponCustomer=CouponCustomer::find()->where(['coupon_code'=>$code])->one();
        if(!empty($couponCustomer)){
            return false;
        }
       // $code_able = self::checkCouponCode($code);
       // if($code_able){
       //     return false;
       // }
        $couponCode = self::find()->where(['coupon_code'=>$code])->one();
        if($couponCode == NULL){
            return false;
        }
        $coupon = Coupon::findOne($couponCode->coupon_id);
        if($coupon == NULL){
            return false;
        }
        if($coupon->coupon_begin_at > time()) return false;
        if($coupon->is_disabled) return false;
        if($coupon->is_del) return false;
        switch($coupon->coupon_time_type){
            case 0:
            if($coupon->coupon_end_at < time()) return false;
            break;
            case 1;
            if($coupon->coupon_get_end_at < time()) return false;
            break;
        }
        //erate coupon for customer
        $couponCustomer = new CouponCustomer;
        $couponCustomer->customer_id = $customer->id;
        $couponCustomer->coupon_id = $coupon->id;
        $couponCustomer->coupon_code_id = $couponCode->id;
        $couponCustomer->coupon_code = $couponCode->coupon_code;
        $couponCustomer->coupon_name = $coupon->coupon_name;
        $couponCustomer->coupon_price = $coupon->coupon_price;
        
        $expirate_at = 0;
        switch($coupon->coupon_time_type){
            case 0:
            $expirate_at = $coupon->coupon_end_at;
            break;
            case 1;
            $expirate_at = time() + $coupon->coupon_use_end_days * 3600 * 24;
        }
        $couponCustomer->expirate_at = $expirate_at;
        $couponCustomer->is_used = 0;
        $couponCustomer->created_at = time();
        $couponCustomer->updated_at = 0;
        $couponCustomer->is_del = 0;
        $couponCustomer->validate();
        if($couponCustomer->hasErrors()){
            return false;
        }
        $couponCustomer->save();
        return $couponCustomer;
                     
    }
    /**
     * 当前码绑定手机号
     * @author CoLee
     */
    public function bindMobile($mobile)
    {
        $customer = Customer::findOne(['customer_phone'=>$mobile]);
        if(empty($customer)){
            throw new InvalidParamException('手机号未注册');
        }
        $model = CouponCustomer::findOne(['coupon_code_id'=>$this->id]);
        if(!empty($model)){
            throw new InvalidParamException('优惠码已被绑定');
        }
        $model = new CouponCustomer();
        $etime = Coupon::getExpirateAtByTimeType($this->coupon_id);
        $model->setAttributes([
            'customer_id'=>$customer->id,
            'coupon_id'=>$this->coupon_id,
            'coupon_code_id'=>$this->id,
            'coupon_code'=>$this->coupon_code,
            'coupon_name'=>$this->coupon_name,
            'coupon_price'=>$this->coupon_price,
            'expirate_at'=>$etime,
        ]);
        if($model->save()){
            return $model;
        }else{
            throw new InvalidParamException('绑定失败');
        }
    }
}
