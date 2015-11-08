<?php
/**
* 优惠券规则控制器
* @date: 2015-11-7
* @author: peak pan
* @return:
**/

namespace core\models\operation\coupon;

use Yii;
use yii\base\InvalidParamException;
use core\models\operation\coupon\CouponCode;
use core\models\operation\coupon\CouponCustomer;
use core\models\operation\coupon\CouponUserinfo;

class CouponRule extends \dbbase\models\operation\coupon\CouponRule
{

	
	
    /**
    * 根据规则id获取单个优惠券的规则详情
    * @date: 2015-11-7
    * @author: peak pan
    * @return:
    **/
	public static function getCouponBasicInfoById($coupon_id){
		
		$coupon = self::find()
		->select(['id as coupon_id', 'couponrule_name as coupon_name', 'couponrule_price as coupon_price'])
		->where(['id'=>$coupon_id])->asArray()->one();
		
		return $coupon;
	}
    
	
	
	
	/**
	* 根据优惠码获取优惠规则详情     
	* @使用人 魏北南 
	* @date: 2015-11-7
	* @author: peak pan
	* @return:
	**/
	
    
    public static function getcouponBasicInfoByCode($coupon_code){
    	
    	
    	
        $coupon_code_arr = CouponUserinfo::find()->select(['id', 'coupon_userinfo_id as coupon_id', 'coupon_userinfo_name as coupon_name', 'coupon_userinfo_price as coupon_price', 'coupon_userinfo_code as coupon_code '])->where(['coupon_code'=>$coupon_code])->asArray()->one();
        
        return $coupon_code_arr;
    }

    
    
    
    
	/**
	* 根据客户id  ，服务类别 获取用户可以使用的优惠券的list，如果是过期或不可用就不显示
	* @date: 2015-11-7
	* @author: peak pan
	* @return:
	**/
	public static function getAbleCouponByCateId($customer_id, $cate_id){
		$able_coupons = (new \yii\db\Query())
			->select(['c.id', 'cc.customer_id', 'c.couponrule_name as coupon_name', 'c.couponrule_price as coupon_price', 'cc.coupon_userinfo_code as coupon_code'])
			->from(['cc'=>'{{%coupon_userinfo}}'])
			->leftJoin(['c'=>'{{%coupon_rule}}'], 'c.id = cc.coupon_userinfo_id')
			->where(['cc.customer_id'=>$customer_id])
			->andWhere(['cc.is_used'=>0])
			->andWhere(['cc.is_del'=>0])
			->andWhere(['c.is_disabled'=>0])
			->andWhere(['c.is_del'=>0])
			->andWhere(['<', 'c.couponrule_use_start_time', time()])
			->andWhere(['>', 'c.couponrule_use_end_time', time()])
			->andWhere(['or', ['and', 'c.couponrule_type=1', 'c.couponrule_service_type_id='.$cate_id], ['c.couponrule_type'=>0]])
			->all();
		
		return $able_coupons;
	}

	
    /**
    * 根据客户的uid 开始使用优惠券     （ 应该是领取优惠券吗）
    * @date: 2015-11-7
    * @author: peak pan
    * @return:
    **/
	
	
	public static function useCoupon($coupon_customer_id){
		
		$couponCustomer = CouponUserinfo::findOne($coupon_customer_id);
		if($couponCustomer === NULL){
			return ['response'=>'error', 'errcode'=>1, 'errmsg'=>'优惠券不存在'];
		}
		if($couponCustomer->is_used == 1){
			return ['response'=>'error', 'errcode'=>2, 'errmsg'=>'优惠券已经使用'];
		}
		
		
		$couponCustomer->is_used = 1;
		$couponCustomer->coupon_userinfo_usetime = time();
		
		if(!$couponCustomer->validate()){
			return ['response'=>'error', 'errcode'=>3, 'errmsg'=>'使用优惠券失败'];
		}
		
		$couponCustomer->save();
		return ['response'=>'success', 'errcode'=>0, 'errmsg'=>''];
	}

	
	
	/**
	* 根据客户的uid优惠券退换
	* @date: 2015-11-7
	* @author: peak pan
	* @return:
	**/
	
	public static function backCoupon($coupon_customer_id){
		$couponCustomer = CouponUserinfo::findOne($coupon_customer_id);
		
		if($couponCustomer === NULL){
			return ['response'=>'error', 'errcode'=>1, 'errmsg'=>'优惠券不存在'];
		}
		if($couponCustomer->is_used == 0){
			return ['response'=>'error', 'errcode'=>2, 'errmsg'=>'数据错误'];
		}
		$couponCustomer->is_used = 0;
		$couponCustomer->updated_at = time();
		if(!$couponCustomer->validate()){
			return ['response'=>'error', 'errcode'=>3, 'errmsg'=>'退还优惠券失败'];
		}
		$couponCustomer->save();
		return ['response'=>'success', 'errcode'=>0, 'errmsg'=>'退还成功'];
	}

	
	
	
	
	
	
	
	
	
//////////////////////////////////不是道强开发////////////////////////////////////////
    /**
     * 优惠名称 
     * @param int  $coupon_id 优惠码规则id
     * @return array 优惠信息
     */
	
	
    public static function getCoupon($coupon_id,$city_name)
    {
        return Coupon::find()->select('coupon_name,coupon_price,coupon_type_name,coupon_service_type_id,coupon_service_type_name,coupon_service_id,coupon_service_name')->where(["id" => $coupon_id,"coupon_city_name" => $city_name])->asArray()->all();
    }
    
    
    
    
     /**
     * 获取优惠券有效时间类型
     */
    public static function couponTimeType($coupon_id)
    {
        $coupon = self::findOne($coupon_id);
        if ($coupon == NULL) {
            return false;
        }
        return array(
            'coupon_time_type' => $coupon->coupon_time_type,
        );
    }
    
    
    
	/**
	 * 赔付类型的绑定记录
	 * @author CoLee
	 */
	public function getBindLog()
	{
	    $bindM = CouponCustomer::find()
	    ->where([
	        'coupon_id'=>$this->id
	    ])->one();
	    return $bindM;
	}
	/**
	 * 是否可以绑定
	 * @author CoLee
	 */
	public function whetherCanBind()
	{
	    return $this->coupon_category==1 && empty($this->getBindLog());
	}
	
	
/////////////////////////////////////////////////////////////////////////////
	
	
	
	
	
	
	
}
