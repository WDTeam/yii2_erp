<?php

namespace dbbase\models\operation;

use Yii;

/**
 * This is the model class for table "ejj_coupon_userinfoceshi".
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
 * @property integer $couponrule_use_end_time
 * @property string $order_code
 * @property integer $system_user_id
 * @property string $system_user_name
 * @property integer $is_used
 * @property string $created_at
 * @property string $updated_at
 * @property integer $is_del
 * @property string $city_name
 * @property string $order_type
 */
class CouponUserinfoceshi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ejj_coupon_userinfoceshi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'coupon_userinfo_id', 'coupon_userinfo_gettime', 'coupon_userinfo_usetime', 'couponrule_use_end_time', 'system_user_id', 'is_used', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['coupon_userinfo_name'], 'string'],
            [['coupon_userinfo_price'], 'number'],
            [['customer_tel'], 'string', 'max' => 15],
            [['coupon_userinfo_code', 'system_user_name', 'city_name', 'order_type'], 'string', 'max' => 40],
            [['order_code'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'customer_id' => Yii::t('app', '客户id'),
            'customer_tel' => Yii::t('app', '客户手机号'),
            'coupon_userinfo_id' => Yii::t('app', '优惠规则id'),
            'coupon_userinfo_code' => Yii::t('app', '优惠码'),
            'coupon_userinfo_name' => Yii::t('app', '优惠券名称'),
            'coupon_userinfo_price' => Yii::t('app', '优惠券价值'),
            'coupon_userinfo_gettime' => Yii::t('app', '领取时间'),
            'coupon_userinfo_usetime' => Yii::t('app', '使用时间'),
            'couponrule_use_end_time' => Yii::t('app', '过期时间'),
            'order_code' => Yii::t('app', '如果已经使用订单号'),
            'system_user_id' => Yii::t('app', '绑定人id'),
            'system_user_name' => Yii::t('app', '绑定人名称'),
            'is_used' => Yii::t('app', '是否已经使用'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '修改时间'),
            'is_del' => Yii::t('app', '状态'),
            'city_name' => Yii::t('app', '城市'),
            'order_type' => Yii::t('app', '类别名称'),
        ];
    }
}
