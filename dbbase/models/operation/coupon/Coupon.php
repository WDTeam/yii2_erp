<?php

namespace common\models\operation\coupon;


use Yii;

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
class Coupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			['coupon_price', function ($attribute, $params) {
                if (!is_numeric($this->$attribute)) {
                    $this->addError($attribute, '优惠券金额不是数字');
                }
				if ($this->$attribute <= 0) {
				    $this->addError($attribute, '优惠券金额不能小于或等于0');
				}
            }],
//			['coupon_order_min_price', function ($attribute, $params) {
 //               if (!is_numeric($this->$attribute)) {
   //                 $this->addError($attribute, '优惠券金额不是数字');
     //           }
	//			if ($this->$attribute <= 0) {
	//			    $this->addError($attribute, '优惠券金额不能小于或等于0');
	//			}
     //       }],
			[['coupon_price', 'coupon_category', 'coupon_type', 'coupon_city_limit', 'coupon_customer_type', 'coupon_time_type', 'coupon_promote_type', 'coupon_code_num'], 'required'],
            [['coupon_price', 'coupon_order_min_price'], 'number'],
            [['coupon_category', 'coupon_type', 'coupon_service_type_id', 'coupon_service_id', 'coupon_city_limit', 'coupon_city_id', 'coupon_customer_type', 'coupon_time_type', 'coupon_use_end_days', 'coupon_promote_type', 'coupon_code_num', 'is_disabled', 'created_at', 'updated_at', 'is_del', 'system_user_id'], 'integer'],
			//[['coupon_code_num', 'coupon_code_max_customer_num'], ['min'=>0]],
            [['coupon_name', 'coupon_category', 'coupon_type_name', 'coupon_service_type_name', 'coupon_service_name', 'coupon_city_name', 'coupon_customer_type_name', 'coupon_time_type_name', 'coupon_promote_type_name', 'system_user_name'], 'string'],
			
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'coupon_name' => '优惠券名称',
            'coupon_price' => '优惠券价值',
            'coupon_type' => '优惠券类型0为全网优惠券1为类别优惠券2为商品优惠券',
            'coupon_type_name' => '优惠券类型名称',
            'coupon_service_type_id' => '服务类别id',
            'coupon_service_type_name' => '服务类别名称',
            'coupon_service_id' => '服务id',
            'coupon_service_name' => '服务名称',
            'coupon_city_limit' => '城市限制0为不限1为单一城市限制',
            'coupon_city_id' => '关联城市',
            'coupon_city_name' => '城市名称',
            'coupon_customer_type' => '适用客户类别逗号分割0为所有用户1为新用户2为老用户3会员4为非会员',
            'coupon_customer_type_name' => '适用客户类别名称',
            'coupon_time_type' => '优惠券有效时间类型0为有效领取时间和有效使用时间一致1为过期时间从领取时间开始计算',
            'coupon_time_type_name' => '优惠券有效时间类型名称',
            'coupon_begin_at' => '开始时间',
            'coupon_end_at' => '领取时间和使用时间一致时的结束时间',
            'coupon_get_end_at' => '领取时间和使用时间不一致时的领取结束时间',
            'coupon_use_end_days' => '领取时间和使用时间不一致时过期天数',
            'coupon_promote_type' => '优惠券优惠类型0为立减1为满减2为每减',
            'coupon_promote_type_name' => '优惠券优惠类型名称',
            'coupon_order_min_price' => '满减或每减时订单最小金额',
            'coupon_code_num' => '优惠码个数',
            //'coupon_code_max_customer_num' => '单个优惠码最大使用人数',
            'is_disabled' => '是否禁用',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'is_del' => '是否逻辑删除',
            'system_user_id' => '系统用户id',
            'system_user_name' => '系统用户名称',
        ];
    }

	/**
 	 * validate coupon_price 
	 */
	public function validateCouponPrice($attribute, $params){
		
		if ($this->$attribute <= 0) {
            $this->addError($attribute, '优惠券金额必须为正数');
        }
		if (!is_number($this->$attribute)) {
            $this->addError($attribute, '优惠券金额必须为数字');
        }
	}

	/**
 	 *	validate coupon_order_min_price
	 */
	public function validateCouponOrderMinPrice($attribute, $params){
		
		if ($this->$attribute <= 0) {
            $this->addError($attribute, '订单最小金额必须为正数');
        }
		if (!is_number($this->$attribute)) {
            $this->addError($attribute, '订单最小金额必须为数字');
        }
	}

}














