<?php

namespace core\models\operation\coupon;

use Yii;
use core\models\operation\coupon\CouponCode;
use yii\base\InvalidParamException;

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

	/**********************************************coupon info***************************************/
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

	/**
     * add coupon and coupon code
	 */ 
	public static function addCoupon($model){
		//var_dump($_POST);
		//exit();
		//$transaction = \Yii::$app->db->beginTransaction();
		//try{
			$couponCode = new CouponCode;
			$service_types = Coupon::getServiceTypes();
			
			//coupon basic info

			//coupon type
			$coupon->coupon_type_name = $service_types[$model->coupon_type];
			switch ($model->coupon_type)
			{
				case 0:
					$model->coupon_service_type_id = 0;
					$model->coupon_service_id = 0;
				break;
				case 1:
					$model->coupon_service_id = 0;
				break;
				case 2:
				
				break;
			
		
				default:
					# code...
				break;
			}
		
		
			//coupon city
			$city_types = Coupon::getCityTypes();
			switch ($model->coupon_city_limit)
			{
				case 0:
					$model->coupon_city_id = 0;
				break;
		
				case 1:
				
				break;
		
		
				default:
					# code...
				break;
			}
			//customer type 
			$customer_types = Coupon::getCustomerTypes();
			$model->coupon_customer_type_name = $customer_types[$model->coupon_customer_type];

			//coupon time type
			$time_types = Coupon::getTimeTypes();
			$model->coupon_time_type_name = $time_types[$model->coupon_time_type];
			switch ($model->coupon_time_type)
			{
				case 0:
					$model->coupon_begin_at = strtotime($_POST['Coupon']['coupon_begin_at']);
					$model->coupon_end_at = strtotime($_POST['Coupon']['coupon_end_at']);
					$model->coupon_get_end_at = 0;
					$model->coupon_use_end_days = 0;
				break;
				case 1:
		            
					$model->coupon_begin_at = strtotime($_POST['Coupon']['coupon_begin_at']);
					$model->coupon_end_at = 0;
					$model->coupon_get_end_at = strtotime($_POST['Coupon']['coupon_get_end_at']);
				
				break;
					
		
				default:
					# code...
				break;
			}
		
			//coupon_promote_type
			$promote_types = Coupon::getPromoteTypes();
			$model->coupon_promote_type_name = $promote_types[$model->coupon_promote_type];
			switch ($model->coupon_promote_type)
			{
				case 0:
					$model->coupon_order_min_price = 0;
				break;
		
				case 1:
				break;
		
				default:
					# code...
				break;
			}


			//coupon other infos
			$model->is_disabled = 0;
			$model->created_at = time();		
			$model->updated_at = 0;
			$model->is_del = 0;
		
			//coupon system user
			$model->system_user_id = 0;
			$model->system_user_name = '';
		    $model->save();

			var_dump($model);
			exit();
		
		    //insert into coupon code
		    $couponCode->coupon_id = $model->id;
		    $couponCode->coupon_name = $model->coupon_name;
		    $couponCode->coupon_price = $model->coupon_price;
		    $couponCode->created_at = time();
		    $couponCode->updated_at = 0;
		    $couponCode->is_del = 0;
		    for($i=0; $i<$_POST['Coupon']['coupon_code_num']; $i++){
		        $coupon_code_str = CouponCode::generateCouponCode();
		        $couponCodeTemp =  CouponCode::find()->where(['coupon_code'=>$coupon_code_str])->one();
		        while($couponCodeTemp){
		            
		             $coupon_code_str = CouponCode::generateCouponCode();
		             $couponCodeTemp =  CouponCode::find()->where(['coupon_code'=>$coupon_code_str])->one();
		        }
		        $couponCode->coupon_code = $coupon_code_str;
		        $couponCode->save(); 
		    }
			//$transaction->commit();
			//return true;
		//}catch(\Exception $e){
			//$transaction->rollback();
			//return false;
		//}
	}

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
	public function getCodes()
	{
	    $models = CouponCode::find()
	    ->where(['coupon_id'=>$this->id])
	    ->all();
	    return (array)$models;
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
}
