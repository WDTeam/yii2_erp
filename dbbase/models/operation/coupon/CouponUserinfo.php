<?php

namespace dbbase\models\operation\coupon;

use Yii;

/**
 * This is the model class for table "ejj_coupon_userinfo".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $customer_tel
 * @property integer $coupon_userinfo_id
 * @property string $coupon_userinfo_code
 * @property string $coupon_userinfo_name
 * @property string $coupon_userinfo_price
 * @property integer $coupon_userinfo_gettime
 * @property integer $coupon_userinfo_usetime
 * @property integer $coupon_userinfo_endtime
 * @property string $order_code
 * @property integer $system_user_id
 * @property string $system_user_name
 * @property integer $is_used
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CouponUserinfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon_userinfo}}';
    }

    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'coupon_userinfo_id','system_user_id', 'is_used','is_del'], 'integer'],
            [['coupon_userinfo_price'], 'number'],
            [['customer_tel'], 'required'],
            [['coupon_userinfo_code', 'system_user_name'], 'string', 'max' => 40],
            [['coupon_userinfo_name'], 'string', 'max' => 100],
            [['order_code'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public $coupon_rule_name_id;
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('core', '主键'),
            'customer_id' => Yii::t('core', '客户名称'),
            'customer_tel' => Yii::t('core', '客户手机号'),
            'coupon_userinfo_id' => Yii::t('core', '优惠规则id'),
            'coupon_userinfo_code' => Yii::t('core', '优惠码'),
            'coupon_userinfo_name' => Yii::t('core', '优惠券名称'),
            'coupon_userinfo_price' => Yii::t('core', '优惠券价值'),
            'coupon_userinfo_gettime' => Yii::t('core', '领取时间'),
            'coupon_userinfo_usetime' => Yii::t('core', '使用时间'),
            'couponrule_use_end_time' => Yii::t('core', '过期时间'),
            'couponrule_use_start_time' => Yii::t('core', '开始时间'),//优惠券的用户可使用的开始时间
            'couponrule_use_end_time' => Yii::t('core', '使用结束时间'),//优惠券的用户可使用的结束时间
            'couponrule_classify' => Yii::t('core', '类型'),//1 一码一用  2 一码多用
            'couponrule_category' => Yii::t('core', '分类'),//优惠券分类0为一般优惠券1为赔付优惠券
            'couponrule_type' => Yii::t('core', '优惠券类别'),//优惠券类型0为全网优惠券1为类别优惠券2为商品优惠券
            'couponrule_service_type_id' => Yii::t('core', '服务类别id'),//分类
            'couponrule_commodity_id' => Yii::t('core', '如果是商品优惠券id'),//洗衣的内心id
            'couponrule_city_limit' => Yii::t('core', '地区限制'),//城市限制0为不限1为单一城市限制
            'couponrule_city_id' => Yii::t('core', '关联城市'),
            'couponrule_customer_type' => Yii::t('core', '适用客户类型'),//适用客户类别逗号分割0为所有用户1为新用户2为老用户3会员4为非会员
            'couponrule_use_end_days' => Yii::t('core', '领取后过期天数'),
            'couponrule_promote_type' => Yii::t('core', '优惠类型'),//优惠券优惠类型1为立减2为满减3为每减
            'couponrule_order_min_price' => Yii::t('core', '最小金额'),
            'couponrule_price' => Yii::t('core', '满减或每减时订单最小金额'),
            'order_code' => Yii::t('core', '订单号'),//如果已经使用订单号
            'is_disabled' => Yii::t('core', '是否禁用'),
            'system_user_id' => Yii::t('core', '绑定人'),
            'system_user_name' => Yii::t('core', '绑定人名称'),
            'is_used' => Yii::t('core', '是否已经使用'),
            'created_at' => Yii::t('core', '创建时间'),
            'updated_at' => Yii::t('core', '更新时间'),
            'coupon_rule_name_id' => Yii::t('core', '优惠券规则'),
            'is_del' => Yii::t('core', '是否逻辑删除'),
        ];
    } 
    
}
