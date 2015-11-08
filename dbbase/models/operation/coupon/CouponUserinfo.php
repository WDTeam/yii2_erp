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
            'customer_id' => Yii::t('core', '客户id'),
            'customer_tel' => Yii::t('core', '客户手机号'),
            'coupon_userinfo_id' => Yii::t('core', '优惠规则id'),
            'coupon_userinfo_code' => Yii::t('core', '优惠码'),
            'coupon_userinfo_name' => Yii::t('core', '优惠券名称'),
            'coupon_userinfo_price' => Yii::t('core', '优惠券价值'),
            'coupon_userinfo_gettime' => Yii::t('core', '领取时间'),
            'coupon_userinfo_usetime' => Yii::t('core', '使用时间'),
            'coupon_userinfo_endtime' => Yii::t('core', '过期时间'),
            'order_code' => Yii::t('core', '订单号'),//如果已经使用订单号
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
