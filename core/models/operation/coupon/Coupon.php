<?php

namespace core\models\operation\coupon;

use Yii;
use yii\base\InvalidParamException;

use core\models\operation\coupon\CouponCode;
use core\models\operation\coupon\CouponCustomer;

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
class Coupon extends \dbbase\models\operation\coupon\Coupon
{

	/**********************************************coupon rules************************************/

	/**
     * get coupon rule service types
     */
    public static function getCategories()
    {
        return array(
            0 => '一般优惠券',
            1 => '赔付优惠券',
        );
    }

    /**
     * get coupon rule service types
     */
    public static function getServiceTypes()
    {
        return array(
            0 => '全网优惠券',
            1 => '类别优惠券',
            //2 => '商品优惠券',
        );
    }

    /**
     * get coupon rule service
     */
    public static function getServiceInfo($id)
    {
        $coupon = self::findOne($id);
        if ($coupon == NULL) {
            return false;
        }
        return array(
            'coupon_type' => $coupon->coupon_type,
            'coupon_type_name' => $coupon->coupon_type_name,
            'coupon_service_type_id' => $coupon->coupon_service_type_id,
            'coupon_service_type_name' => $coupon->coupon_service_type_name,
            'coupon_service_id' => $coupon->coupon_service_id,
            'coupon_service_name' => $coupon->coupon_service_name,
        );
    }

    /**
     * get coupon rule customer city types
     */
    public static function getCityTypes()
    {
        return array(
            0 => '全部城市',
            //1 => '单个城市',
        );
    }

    /**
     * get coupon rule city 
     */
    public static function getCityInfo($id)
    {
        $coupon = self::findOne($id);
        if ($coupon == NULL) {
            return false;
        }
        return array(
            'coupon_city_limit' => $coupon->coupon_city_limit,
            'coupon_city_id' => $coupon->coupon_city_id,
            'coupon_city_name' => $coupon->coupon_city_name,
        );
    }

	/**
     * get service categories
	 */
	public static function getServiceCates(){
		$serviceCates = \core\models\operation\OperationCategory::getAllCategory();
		$service_cates = [];
		foreach ($serviceCates as $value)
		{
			# code...
			$service_cate_id = $value['id'];
			$service_cate_name = $value['operation_category_name'];
			$service_cates[] = [
				'service_cate_id'=>$service_cate_id,
				'service_cate_name'=>$service_cate_name,
			];
		}
		return $service_cates;
	}

    /**
     * get coupon rule customer types
     */
    public static function getCustomerTypes()
    {
        return array(
            0 => '所有用户',
            //1 => '新用户',
            //2 => '老用户',
            //3 => '会员',
            //4 => '非会员',
        );
    }

    /**
     * get coupon rule customer type
     */
    public static function getCustomerTypeInfo($id)
    {
        $coupon = self::findOne($id);
        if ($coupon == NULL) {
            return false;
        }
        return array(
            'coupon_customer_type' => $coupon->coupon_customer_type,
            'coupon_customer_type_name' => $coupon->coupon_customer_type_name,
        );
    }

    /**
     * get coupon rule coupon time types
     */
    public static function getTimeTypes()
    {
        return array(
            0 => '领取时间段和使用时间段一致',
            //1 => '使用时间段领取后开始计算',
        );
    }

    /**
     * get coupon rule coupon promote types
     */
    public static function getPromoteTypes()
    {
        return array(
            0 => '立减',
            //1 => '满减',
            //2 => '每减',
        );
    }

	/**********************************************coupon time***************************************/
	/**
     * get coupon getting end time
	 */
	public static function getEndGettingAt($coupon_id){
		$coupon = self::findOne($coupon_id);
		if($coupon === NULL) return false;

		$end_getting_at = 0;
		switch ($coupon->coupon_time_type)
		{
			case 0:
				$end_getting_at = $coupon->coupon_end_at;
			break;
		
			case 1:
				$end_getting_at = $coupon->coupon_get_end_at;
			break;
		
			default:
				# code...
			break;
		}
		return $end_getting_at;
	}

	/**
     * get coupon use end time
	 */
	public static function getEndUsingAt($coupon_id){
		$coupon = self::findOne($coupon_id);
		if($coupon === NULL) return false;

		$end_getting_at = 0;
		switch ($coupon->coupon_time_type)
		{
			case 0:
				$end_getting_at = $coupon->coupon_end_at;
			break;
		
			case 1:
				$end_getting_at = $coupon->coupon_get_end_at + 24 * 3600 * $coupon->coupon_use_end_days;
			break;
		
			default:
				# code...
			break;
		}
		return $end_getting_at;
	}

	/**
     * get coupon code expirate_at by time type
	 */
	public static function getExpirateAtByTimeType($coupon_id, $get_at=null){
		$coupon = self::findOne($coupon_id);
		if($coupon == NULL) return false;

		if(empty($get_at)) $get_at = time();

		$expirate_at = 0;
		switch ($coupon->coupon_time_type)
		{
			case 0;
				if($get_at > $coupon->coupon_begin_at && $get_at < $coupon->coupon_end_at){
					$expirate_at = $coupon->coupon_end_at;
				}else{
					throw new InvalidParamException('优惠卷已过期');
				}
			break;
		
			case 1;
				if($get_at > $coupon->coupon_begin_at && $get_at < $coupon->coupon_end_at){
					$expirate_at =  $get_at + 24 * 3600 * $coupon->coupon_use_end_days;
				}else{
					throw new InvalidParamException('优惠卷已过期');
				}
				
			break;
		
			default:
				throw new InvalidParamException('无效的时间类型');
			break;
		}
		return $expirate_at;
	}

	/**************************************defined relations*************************************/
	/**
     * define relation of code for coupon
	 */
	public function getCouponCodeRelation(){
		return $this->hasMany(CouponCode::className(), ['coupon_id' => 'id']);
	}

	/**
     * define relation of customer for coupon
	 */
	public function getCouponCustomerRelation(){
		return $this->hasMany(CouponCustomer::className(), ['coupon_id' => 'id']);
	}


	/**************************************coupon for order**************************************/
	/**
     * get customer coupon by cate_id
	 */
	public static function getAbleCouponByCateId($customer_id, $cate_id){
		$able_coupons = (new \yii\db\Query())
			->select(['c.id', 'cc.customer_id', 'c.coupon_name', 'c.coupon_price', 'cc.coupon_code'])
			->from(['cc'=>'{{%coupon_customer}}'])
			->leftJoin(['c'=>'{{%coupon}}'], 'c.id = cc.coupon_id')
			->where(['cc.customer_id'=>$customer_id])
			->andWhere(['cc.is_used'=>0])
			->andWhere(['cc.is_del'=>0])
			->andWhere(['c.is_disabled'=>0])
			->andWhere(['c.is_del'=>0])
			->andWhere(['<', 'c.coupon_begin_at', time()])
			->andWhere(['>', 'c.coupon_end_at', time()])
			->andWhere(['or', ['and', 'c.coupon_type=1', 'c.coupon_service_type_id='.$cate_id], ['c.coupon_type'=>0]])
			->all();
		return $able_coupons;
	}

	/**
     * get coupon basic info by id
	 */
	public static function getCouponBasicInfoById($coupon_id){
		$coupon = self::find()->select(['id as coupon_id', 'coupon_name', 'coupon_price'])->where(['id'=>$coupon_id])->asArray()->one();
		return $coupon;
	}

	

	/**
     * set custoemr's coupon used after pay by coupon
	 */
	public static function useCoupon($coupon_customer_id){
		$couponCustomer = CouponCustomer::findOne($coupon_customer_id);
		if($couponCustomer === NULL){
			return ['response'=>'error', 'errcode'=>1, 'errmsg'=>'优惠券不存在'];
		}
		if($couponCustomer->is_used == 1){
			return ['response'=>'error', 'errcode'=>2, 'errmsg'=>'数据错误'];
		}
		$couponCustomer->is_used = 1;
		$couponCustomer->updated_at = time();
		if(!$couponCustomer->validate()){
			return ['response'=>'error', 'errcode'=>3, 'errmsg'=>'使用优惠券失败'];
		}
		$couponCustomer->save();
		return ['response'=>'success', 'errcode'=>0, 'errmsg'=>''];
	}

	/************************************coupon reverse******************************************/
	/**
     * reverse coupon while customer cancel order
	 */
	public static function backCoupon($coupon_customer_id){
		$couponCustomer = CouponCustomer::findOne($coupon_customer_id);
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
		return ['response'=>'success', 'errcode'=>0, 'errmsg'=>''];
	}

	/***********************************coupon code properties*******************************************/
	/**
     * check whether the coupon code is able to be binded
     */
	public static function isBinded($coupon_code){
		$couponCustomer = CouponCustomer::find()->where(['coupon_code'=>$coupon_code])->one();
		if($couponCustomer === NULL) {
			return false;
		}else{
			return true;
		}
	}

	/**
     * check whether the coupon code is expirated
	 */
	//public static function isExpirated($coupon_code){
	//	$coupon_code = (new \yii\db\Query())
	//		->select(['cc.coupon_id as coupon_id', 'cc.id as coupon_code_id', 'c.coupon_name', 'c.coupon_price', 'cc.coupon_code', 'c.coupon_time_type', 'c.coupon_begin_at', 'c.coupon_end_at', 'c.coupon_get_end_at', 'c.coupon_use_end_days'])
	//		->from(['cc'=>'{{%coupon_code}}'])
	//		->leftJoin(['c'=>'{{%coupon}}'], 'c.id = cc.coupon_id')
	//		->where(['cc.coupon_code'=>$coupon_code])
	//		->one();
	//	$is_expirated = true;
	//	switch ($coupon_code['coupon_time_type'])
	//	{
	//		case 0:
	//			if($coupon_code['coupon_end_at'] < time()){
	//				$is_expirated = true;
	//			}else{
	//				$is_expirated = false;
	//			}
	//		break;
	//		case 1:
	//			if($coupon_code['coupon_end_at'] < time()){
	//				$is_expirated = true;
	//			}else{
	//				$is_expirated = false;
	//			}
	//		break;
	//		
	//	
	//		default:
	//			# code...
	//		break;
	//	}
	//}

	/**
     * coupon generated only one bundle for customer by phone
	 */
	public static function bindByPhone($coupon_code, $phone){
		
	}


	/**********************************coupon other msg*******************************************/

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
     * 获取当前优惠券的所有优惠码
     * @author CoLee
     */
	//public function getCodes()
	//{
	//    $models = CouponCode::find()
	//    ->where(['coupon_id'=>$this->id])
	//    ->all();
	//    return (array)$models;
	//}
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
}
