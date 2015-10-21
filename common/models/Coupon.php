<?php

namespace common\models;

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
            [['coupon_price', 'coupon_order_min_price'], 'number'],
            [['coupon_type', 'coupon_service_type_id', 'coupon_service_id', 'coupon_city_limit', 'coupon_city_id', 'coupon_customer_type', 'coupon_time_type', 'coupon_begin_at', 'coupon_end_at', 'coupon_get_end_at', 'coupon_use_end_days', 'coupon_promote_type', 'coupon_code_num', 'coupon_code_max_customer_num', 'is_disabled', 'created_at', 'updated_at', 'is_del', 'system_user_id'], 'integer'],
            [['coupon_name', 'coupon_type_name', 'coupon_service_type_name', 'coupon_service_name', 'coupon_city_name', 'coupon_customer_type_name', 'coupon_promote_type_name', 'system_user_name'], 'string', 'max' => 255],
            [['coupon_time_type_name'], 'string', 'max' => 4]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', '主键'),
            'coupon_name' => Yii::t('common', '优惠券名称'),
            'coupon_price' => Yii::t('common', '优惠券价值'),
            'coupon_type' => Yii::t('common', '优惠券类型0为全网优惠券1为类别优惠券2为商品优惠券'),
            'coupon_type_name' => Yii::t('common', '优惠券类型名称'),
            'coupon_service_type_id' => Yii::t('common', '服务类别id'),
            'coupon_service_type_name' => Yii::t('common', '服务类别名称'),
            'coupon_service_id' => Yii::t('common', '服务id'),
            'coupon_service_name' => Yii::t('common', '服务名称'),
            'coupon_city_limit' => Yii::t('common', '城市限制0为不限1为单一城市限制'),
            'coupon_city_id' => Yii::t('common', '关联城市'),
            'coupon_city_name' => Yii::t('common', '城市名称'),
            'coupon_customer_type' => Yii::t('common', '适用客户类别逗号分割0为所有用户1为新用户2为老用户3会员4为非会员'),
            'coupon_customer_type_name' => Yii::t('common', '适用客户类别名称'),
            'coupon_time_type' => Yii::t('common', '优惠券有效时间类型0为有效领取时间和有效使用时间一致1为过期时间从领取时间开始计算'),
            'coupon_time_type_name' => Yii::t('common', '优惠券有效时间类型名称'),
            'coupon_begin_at' => Yii::t('common', '开始时间'),
            'coupon_end_at' => Yii::t('common', '领取时间和使用时间一致时的结束时间'),
            'coupon_get_end_at' => Yii::t('common', '领取时间和使用时间不一致时的领取结束时间'),
            'coupon_use_end_days' => Yii::t('common', '领取时间和使用时间不一致时过期天数'),
            'coupon_promote_type' => Yii::t('common', '优惠券优惠类型0为立减1为满减2为每减'),
            'coupon_promote_type_name' => Yii::t('common', '优惠券优惠类型名称'),
            'coupon_order_min_price' => Yii::t('common', '满减或每减时订单最小金额'),
            'coupon_code_num' => Yii::t('common', '优惠码个数'),
            'coupon_code_max_customer_num' => Yii::t('common', '单个优惠码最大使用人数'),
            'is_disabled' => Yii::t('common', '是否禁用'),
            'created_at' => Yii::t('common', '创建时间'),
            'updated_at' => Yii::t('common', '更新时间'),
            'is_del' => Yii::t('common', '是否逻辑删除'),
            'system_user_id' => Yii::t('common', '系统用户id'),
            'system_user_name' => Yii::t('common', '系统用户名称'),
        ];
    }
}
