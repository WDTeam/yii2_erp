<?php
/**
* 优惠券规则控制器
* @date: 2015-11-7
* @author: peak pan
* @return:
**/

namespace core\models\operation\coupon;

use Yii;
use core\models\operation\coupon\CouponUserinfo;
use core\models\coupon\CouponLog;


class CouponRule extends \dbbase\models\operation\coupon\CouponRule
{

	
	/**
	* 根据优惠码获取优惠规则详情     
	* @使用人 魏北南 
	* @date: 2015-11-7
	* @author: peak pan
	* @return:
	**/
	
    public static function getcouponBasicInfoByCode($coupon_code){
    	
    	if($coupon_code==0){
    		$array=[
    		'is_status'=>0,
    		'msg'=>'亲你忘记传参数了',
    		'data'=>'',
    		];
    		return $array;
    	}
    	
        $coupon_code_arr = CouponUserinfo::find()->select(['id', 'coupon_userinfo_id as coupon_id', 'coupon_userinfo_name as coupon_name', 'coupon_userinfo_price as coupon_price', 'coupon_userinfo_code as coupon_code'])->where(['coupon_code'=>$coupon_code])->asArray()->one();

        if(empty($coupon_code_arr)){
        	$array=[
        	'is_status'=>1,
        	'msg'=>'此优惠码未查到',
        	'data'=>'',
        	];
        	return $array;
        }else{
        	$array=[
        	'is_status'=>1,
        	'msg'=>'查询成功',
        	'data'=>$coupon_code_arr,
        	];
        	return $array;
        }
       
        
    }

     
   /**
   *  使用优惠券  李胜强使用
   * @date: 2015-11-8
   * @author: peak pan
   * @ post （用户ID，优惠券ID，优惠券CODE，优惠券金额，交易记录号）
   * @return: （用户ID，优惠券ID，优惠券CODE，优惠券金额，交易记录号，交易流水号（自己生成，不能重复））   
   **/

    public static function get_couponinfo($customer_id,$couponrule_id,$couponrule_price,$pay_nub,$order_code){
    	
    	
    	$coupon_code_arr = CouponUserinfo::find()
    	->where(['customer_id'=>$customer_id,'coupon_userinfo_id'=>$couponrule_id,'coupon_userinfo_price'=>$couponrule_price])
    	->asArray()
    	->one();
    	
    	if($coupon_code_arr){
    	//开始修改状态		
    	$coupon_code_arr->coupon_userinfo_usetime=time();
    	$coupon_code_arr->order_code=$order_code;
    	$coupon_code_arr->is_used=1;
    	$coupon_code_arr->save();
    	
    	$Couponlogobj=new CouponLog;
    	$Couponlogobj->customer_id=$customer_id;
    	$Couponlogobj->order_id=$order_code;
    	$Couponlogobj->coupon_id=$couponrule_id;
    	$Couponlogobj->coupon_code_id=0;
    	$Couponlogobj->coupon_code=$coupon_code_arr['coupon_userinfo_code'];
    	$Couponlogobj->coupon_name=$coupon_code_arr['coupon_userinfo_name'];
    	$Couponlogobj->coupon_price=$couponrule_price;
    	$Couponlogobj->coupon_log_type=2;
    	$Couponlogobj->coupon_log_type_name=$coupon_code_arr['coupon_userinfo_name'];
    	$Couponlogobj->coupon_log_price=$couponrule_price;
    	$Couponlogobj->created_at=time();
    	$Couponlogobj->updated_at=time();
    	$Couponlogobj->is_del=0;
    	$Couponlogobj->save();
    	
    	if($Couponlogobj->id<>0 && $Couponlogobj->id<>''){
    		
    		$coupon_code=[
    		'customer_id'=>$coupon_code_arr['customer_id'],
    		'coupon_userinfo_id'=>$coupon_code_arr['coupon_userinfo_id'],
    		'coupon_userinfo_code'=>$coupon_code_arr['coupon_userinfo_code'],
    		'coupon_userinfo_price'=>$coupon_code_arr['coupon_userinfo_price'],
    		'payment_id'=>$pay_nub,
    		'transaction_id'=>$Couponlogobj->id
    		];
    		$array=[
    		'is_status'=>1,
    		'msg'=>'优惠券使用成功',
    		'data'=>$coupon_code,
    		];
    		return $array;
    		
    	}else{
    		$array=[
    		'is_status'=>0,
    		'msg'=>'记录日志失败',
    		'data'=>'',
    		];
    		return $array;
    	}

    	
    	return $array;
    	}else{
    	//优惠券数据不匹配
    	$array=[
    		'is_status'=>0,
    		'msg'=>'优惠券数据不匹配',
    		'data'=>'',
    		];
    		return $array;
    	}
    		
    }

    
	/**
	* 根据客户id  ，服务类别 获取用户可以使用的优惠券的list，如果是过期或不可用就不显示   
	* 林红优使用
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
			->asArray()
			->all();
		
	 if(empty($able_coupons)){
        	$array=[
        	'is_status'=>1,
        	'msg'=>'此顾客无优惠码可用',
        	'data'=>'',
        	];
        	return $array;
        }else{
        	$array=[
        	'is_status'=>1,
        	'msg'=>'查询成功',
        	'data'=>$able_coupons,
        	];
        	return $array;
        }
		
	}

	
	/**
	 * 根据规则id获取单个优惠券的规则详情  
	 * 林洪优使用
	 * @date: 2015-11-7
	 * @author: peak pan
	 * @return:
	 **/

	public static function getCouponBasicInfoById($coupon_id){
	
		if($coupon_id==0){
			$array=[
			'is_status'=>1,
			'msg'=>'亲,请传入规则id',
			'data'=>'',
			];
			return $array;
		}
		
		$coupon = self::find()
		->select(['id as coupon_id', 'couponrule_name as coupon_name', 'couponrule_price as coupon_price'])
		->where(['id'=>$coupon_id])
		->asArray()
		->one();
	
	if(empty($coupon)){
        	$array=[
        	'is_status'=>1,
        	'msg'=>'查无此规则',
        	'data'=>'',
        	];
        	return $array;
        }else{
        	$array=[
        	'is_status'=>1,
        	'msg'=>'查询成功',
        	'data'=>$coupon,
        	];
        	return $array;
        }
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


		
}
