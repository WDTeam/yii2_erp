<?php
/**
* 优惠券规则数据库映射关系 
* ==========================
* 北京一家洁 版权所有 2015-2018 
* ----------------------------
* 这不是一个自由软件，未经授权不许任何使用和传播。
* ==========================
* @date: 2015-11-12
* @author: peak pan 
* @version:1.0
*/

namespace dbbase\models\operation\coupon;

use Yii;

/**
 * This is the model class for table "ejj_coupon_rule".
 *
 * @property integer $id
 * @property string $couponrule_name
 * @property string $couponrule_channelname
 * @property integer $couponrule_classify
 * @property integer $couponrule_category
 * @property string $couponrule_category_name
 * @property integer $couponrule_type
 * @property string $couponrule_type_name
 * @property integer $couponrule_service_type_id
 * @property string $couponrule_service_type_name
 * @property integer $couponrule_commodity_id
 * @property string $couponrule_commodity_name
 * @property integer $couponrule_city_limit
 * @property integer $couponrule_city_id
 * @property string $couponrule_city_name
 * @property integer $couponrule_customer_type
 * @property string $couponrule_customer_type_name
 * @property integer $couponrule_get_start_time
 * @property integer $couponrule_get_end_time
 * @property integer $couponrule_use_start_time
 * @property integer $couponrule_use_end_time
 * @property string $couponrule_code
 * @property string $couponrule_Prefix
 * @property integer $couponrule_use_end_days
 * @property integer $couponrule_promote_type
 * @property string $couponrule_promote_type_name
 * @property string $couponrule_order_min_price
 * @property string $couponrule_price
 * @property string $couponrule_price_sum
 * @property integer $couponrule_code_num
 * @property integer $couponrule_code_max_customer_num
 * @property integer $is_disabled
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 * @property integer $system_user_id
 * @property string $system_user_name
 */
class CouponRule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon_rule}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['couponrule_classify', 'couponrule_category', 'couponrule_type', 'couponrule_service_type_id', 'couponrule_commodity_id', 'couponrule_city_limit', 'couponrule_city_id', 'couponrule_use_end_days', 'couponrule_promote_type', 'couponrule_code_num', 'couponrule_code_max_customer_num', 'is_disabled', 'created_at', 'updated_at', 'is_del', 'system_user_id'], 'integer'],
            [['couponrule_order_min_price', 'couponrule_price'], 'number'],
            [['couponrule_name','couponrule_category_name', 'couponrule_type_name', 'couponrule_service_type_name', 'couponrule_commodity_name', 'couponrule_customer_type_name'], 'string', 'max' => 100],
            [['couponrule_channelname'], 'string', 'max' => 80],
            [['couponrule_city_name', 'couponrule_promote_type_name'], 'string', 'max' => 60],
            [['couponrule_code', 'system_user_name'], 'string', 'max' => 40],
            [['couponrule_Prefix'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('core', '主键'),
            'couponrule_name' => Yii::t('core', '优惠券名称'),
            'couponrule_channelname' => Yii::t('core', '渠道名称'),//(主要使用到一码多用分渠道发)
            'couponrule_classify' => Yii::t('core', '类型'),//1 一码一用  2 一码多用

            'couponrule_category' => Yii::t('core', '分类'),//优惠券分类0为一般优惠券1为赔付优惠券
            'couponrule_category_name' => Yii::t('core', '优惠券范畴'),
            'couponrule_type' => Yii::t('core', '优惠券类别'),//优惠券类型0为全网优惠券1为类别优惠券2为商品优惠券
            'couponrule_type_name' => Yii::t('core', '优惠券类型名称'),
            'couponrule_service_type_id' => Yii::t('core', '服务类别id'),//分类
            'couponrule_service_type_name' => Yii::t('core', '服务类别名称'),//分类 名称
            'couponrule_commodity_id' => Yii::t('core', '如果是商品优惠券id'),//洗衣的内心id
            'couponrule_commodity_name' => Yii::t('core', '如果是商品名称'),//洗衣
            'couponrule_city_limit' => Yii::t('core', '地区限制'),//城市限制0为不限1为单一城市限制
            'couponrule_city_id' => Yii::t('core', '关联城市'),
            'couponrule_city_name' => Yii::t('core', '城市名称'),
            'couponrule_customer_type' => Yii::t('core', '适用客户类型'),//适用客户类别逗号分割0为所有用户1为新用户2为老用户3会员4为非会员
            'couponrule_customer_type_name' => Yii::t('core', '适用客户类别名称'),
            'couponrule_get_start_time' => Yii::t('core', '可领取开始时间'),
            'couponrule_get_end_time' => Yii::t('core', '可领取结束时间'),
            'couponrule_use_start_time' => Yii::t('core', '使用开始时间'),//优惠券的用户可使用的开始时间
            'couponrule_use_end_time' => Yii::t('core', '使用结束时间'),//优惠券的用户可使用的结束时间
            'couponrule_code' => Yii::t('core', '优惠码'),//如果是1码多用的优惠码
            'couponrule_Prefix' => Yii::t('core', '优惠码前缀'),
            'couponrule_use_end_days' => Yii::t('core', '领取后过期天数'),
            'couponrule_promote_type' => Yii::t('core', '优惠类型'),//优惠券优惠类型0为立减1为满减2为每减
            'couponrule_promote_type_name' => Yii::t('core', '优惠名称'),//优惠券优惠类型名称
            'couponrule_order_min_price' => Yii::t('core', '最小金额'),//满减或每减时订单最小金额
            'couponrule_price' => Yii::t('core', '优惠券单价'),//优惠券单价
            'couponrule_price_sum' => Yii::t('core', '优惠券总价'),//优惠券总价
            'couponrule_code_num' => Yii::t('core', '优惠码数量'),//优惠码个数
            'couponrule_code_max_customer_num' => Yii::t('core', '使用人数限制'),//如果是一码多用单个优惠码最大使用人数限制
            'is_disabled' => Yii::t('core', '是否禁用'),
            'created_at' => Yii::t('core', '创建时间'),
            'updated_at' => Yii::t('core', '更新时间'),
            'is_del' => Yii::t('core', '是否逻辑删除'),
            'system_user_id' => Yii::t('core', '优惠码创建人id'),
            'system_user_name' => Yii::t('core', '优惠码创建人'),
        ];
    }

   
    
    
    
}
