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
        return 'ejj_coupon_userinfo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'coupon_userinfo_id', 'coupon_userinfo_gettime', 'coupon_userinfo_usetime', 'coupon_userinfo_endtime', 'system_user_id', 'is_used', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['coupon_userinfo_price'], 'number'],
            [['customer_tel'], 'string', 'max' => 11],
            [['coupon_userinfo_code', 'system_user_name'], 'string', 'max' => 40],
            [['coupon_userinfo_name'], 'string', 'max' => 100],
            [['order_code'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
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
            'order_code' => Yii::t('core', '如果已经使用订单号'),
            'system_user_id' => Yii::t('core', '绑定人id'),
            'system_user_name' => Yii::t('core', '绑定人名称'),
            'is_used' => Yii::t('core', '是否已经使用'),
            'created_at' => Yii::t('core', '创建时间'),
            'updated_at' => Yii::t('core', '更新时间'),
            'is_del' => Yii::t('core', '是否逻辑删除'),
        ];
    }

    /**
     * @inheritdoc
     * @return EjjQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EjjQuery(get_called_class());
    }
}
