<?php
/**
* 优惠券规则控制器
* ==========================
* 北京一家洁 版权所有 2015-2018 
* ----------------------------
* 这不是一个自由软件，未经授权不许任何使用和传播。
* ==========================
* @date: 2015-11-12
* @author: peak pan 
* @version:1.0
*/

namespace core\models\operation\coupon;

use Yii;
use core\models\operation\coupon\CouponUserinfo;
use core\models\operation\coupon\CouponLog;


class CouponRule extends \dbbase\models\operation\coupon\CouponRule
{

	
	/**
	* 根据优惠码获取优惠规则详情     
	* @使用人 魏北南     测试完成 ok
	* @date: 2015-11-7
	* @author: peak pan
	* @return:
	**/
	
    public static function getcouponBasicInfoByCode($coupon_code){
    	
    	if($coupon_code ==''){
    		$array=[
    		'is_status'=>0,
    		'msg'=>'亲你忘记传参数了',
    		'data'=>'',
    		];
    		return $array;
    	}
        $coupon_code_arr = CouponUserinfo::find()->select(['id', 'coupon_userinfo_id as coupon_id', 'coupon_userinfo_name as coupon_name', 'coupon_userinfo_price as coupon_price', 'coupon_userinfo_code as coupon_code'])->where(['coupon_userinfo_code'=>$coupon_code])->asArray()->one();

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
   * @ post （用户ID，优惠券ID，优惠券金额，交易记录号,点单号 ）
   * @return: （用户ID，优惠券ID，优惠券CODE，优惠券金额，交易记录号，交易流水号（自己生成，不能重复））   
   **/

    public static function get_couponinfo($customer_tel,$couponrule_id,$couponrule_price,$pay_nub,$order_code){
    	
    	
    	$coupon_code_arr = CouponUserinfo::find()
    	->where(['customer_tel'=>$customer_tel,'coupon_userinfo_id'=>$couponrule_id,'coupon_userinfo_price'=>$couponrule_price,'is_used'=>0])
    	->asArray()
    	->one();
    	if($coupon_code_arr){
    	//开始修改状态	
    	$coupon_code=new CouponUserinfo;
    	$coupon_code_edit=$coupon_code->findOne($coupon_code_arr['id']);
    	$coupon_code_edit->coupon_userinfo_usetime=time();
    	$coupon_code_edit->order_code=$order_code;
    	$coupon_code_edit->is_used=1;
    	$coupon_code_edit->save();

    	$Couponlogobj=new CouponLog;
    	$Couponlogobj->customer_code=$coupon_code_edit->customer_code;
    	$Couponlogobj->customer_id=0;
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
    		'transaction_id'=>$coupon_code_edit->customer_code
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
    
	public static function getAbleCouponByCateId($customer_tel, $cate_id){
		$able_coupons = CouponUserinfo::find()
			->select(['id', 'customer_id','customer_tel', 'coupon_userinfo_name as coupon_name', 'couponrule_price as coupon_price', 'coupon_userinfo_code as coupon_code'])
			->where(['customer_tel'=>$customer_tel,'is_used'=>0,'is_del'=>0,'is_disabled'=>0])
			->andWhere(['<', 'couponrule_use_start_time', time()])
			->andWhere(['>', 'couponrule_use_end_time', time()])
			->andWhere(['or', ['and', 'couponrule_type=1', 'couponrule_service_type_id='.$cate_id], ['couponrule_type'=>0]])
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

	public static function getCouponBasicInfoById($customer_tel){
	
		if($customer_tel==0){
			$array=[
			'is_status'=>1,
			'msg'=>'亲,请传入规则id',
			'data'=>'',
			];
			return $array;
		}
		
		$coupon = CouponUserinfo::find()
		->select(['customer_tel', 'coupon_userinfo_name as coupon_name', 'couponrule_price as coupon_price'])
		->where(['customer_tel'=>$customer_tel])
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
	* 根据优惠码+客户id+服务项id 获取优惠码是否可用 
	* 林洪优使用
	* @date: 2015-11-11
	* @author: peak pan
	* @return:如果可用则返回 优惠码金额 优惠码id 否则返回 false
	**/
	
	
	public static function get_is_coupon_status($coupon_code,$customer_tel,$couponrule_service_type_id,$couponrule_commodity_id,$city_id){
		$now_time=time();
		$coupon = CouponUserinfo::find()
		->select('id,coupon_userinfo_price')
		->where(['and',"couponrule_use_end_time>$now_time",'is_del=0','is_used=0',"customer_tel=$customer_tel", ['or', ['and','couponrule_city_limit=1',"couponrule_city_id=$city_id"], 'couponrule_city_limit=0'],['or', ['or','couponrule_type!=0',"couponrule_service_type_id=$couponrule_service_type_id"], 'couponrule_type=0']] )
		->asArray()
		->one();
	
		if(empty($coupon)){
			return false;
		}else{
			return $coupon;
		}
	}
	

	/**
	* 根据客户id获取全部优惠券接口
	* 刘道强使用
	* couponrule_type   实收金额优惠券类型1为全网优惠券2为类别优惠券3为商品优惠券
	* couponrule_service_type_id  服务类别id
	* couponrule_commodity_id    如果是商品优惠券id  
	* @date: 2015-11-11
	* @author: peak pan
	* @return: 返回二维数组，包括优惠券服务品类，优惠券金额优惠券过期时间  （全部包含使用和不使用的）
	**/
	
	public static function getcustomerlist_l($customer_tel){
		$now_time=time();
		
		$couponCustomer = CouponUserinfo::find()		
		->select(['customer_tel','coupon_userinfo_name','coupon_userinfo_price','couponrule_use_start_time','couponrule_use_end_time','couponrule_type','couponrule_service_type_id','couponrule_commodity_id'])
		->where(['and',"customer_tel=$customer_tel"] )
		->orderBy(['couponrule_use_end_time'=>SORT_ASC,'coupon_userinfo_price'=>SORT_DESC])
		->asArray()
		->all();
		if(empty($couponCustomer)){
			$array=[
			'is_status'=>4019,
			'msg'=>'暂无数据',
			'data'=>'',
			];
			return $array;
		}else{
			$array=[
			'is_status'=>1,
			'msg'=>'查询成功',
			'data'=>$couponCustomer,
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
