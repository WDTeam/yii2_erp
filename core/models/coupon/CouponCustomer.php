<?php

namespace core\models\coupon;

use Yii;

/**
 * This is the model class for table "{{%coupon_customer}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $order_id
 * @property integer $coupon_id
 * @property integer $coupon_code_id
 * @property string $coupon_code
 * @property string $coupon_name
 * @property string $coupon_price
 * @property integer $expirate_at
 * @property integer $is_used
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CouponCustomer extends \common\models\coupon\CouponCustomer
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon_customer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'order_id', 'coupon_id', 'coupon_code_id', 'expirate_at', 'is_used', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['coupon_price'], 'number'],
            [['coupon_code', 'coupon_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('coupon', '主键'),
            'customer_id' => Yii::t('coupon', '客户id'),
            'order_id' => Yii::t('coupon', '订单id'),
            'coupon_id' => Yii::t('coupon', '优惠规则id'),
            'coupon_code_id' => Yii::t('coupon', '优惠码id'),
            'coupon_code' => Yii::t('coupon', '优惠码'),
            'coupon_name' => Yii::t('coupon', '优惠券名称'),
            'coupon_price' => Yii::t('coupon', '优惠券价值'),
            'expirate_at' => Yii::t('coupon', '过期时间'),
            'is_used' => Yii::t('coupon', '是否已经使用'),
            'created_at' => Yii::t('coupon', '创建时间'),
            'updated_at' => Yii::t('coupon', '更新时间'),
            'is_del' => Yii::t('coupon', '是否逻辑删除'),
        ];
    }
}
