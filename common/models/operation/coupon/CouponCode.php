<?php

namespace common\models\operation\coupon;

use Yii;

/**
 * This is the model class for table "{{%coupon_code}}".
 *
 * @property integer $id
 * @property integer $coupon_id
 * @property string $coupon_code
 * @property string $coupon_name
 * @property string $coupon_price
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CouponCode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon_code}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coupon_id', 'created_at', 'updated_at', 'is_del'], 'integer'],
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
            'id' => Yii::t('common', '主键'),
            'coupon_id' => Yii::t('common', '优惠规则id'),
            'coupon_code' => Yii::t('common', '优惠码'),
            'coupon_name' => Yii::t('common', '优惠券名称'),
            'coupon_price' => Yii::t('common', '优惠券价值'),
            'created_at' => Yii::t('common', '创建时间'),
            'updated_at' => Yii::t('common', '更新时间'),
            'is_del' => Yii::t('common', '是否逻辑删除'),
        ];
    }
}
