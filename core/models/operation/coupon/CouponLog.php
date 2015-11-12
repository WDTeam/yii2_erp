<?php
/**
* 优惠券对接日志控制器
* ==========================
* 北京一家洁 版权所有 2015-2018 
* ----------------------------
* 这不是一个自由软件，未经授权不许任何使用和传播。
* ==========================
* @date: 2015-11-12
* @author: peak pan 
* @version:1.0
*/

namespace core\models\operation\coupon;

use Yii;

/**
 * This is the model class for table "{{%coupon_log}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $order_id
 * @property integer $coupon_id
 * @property integer $coupon_code_id
 * @property string $coupon_code
 * @property string $coupon_name
 * @property string $coupon_price
 * @property integer $coupon_log_type
 * @property string $coupon_log_type_name
 * @property string $coupon_log_price
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CouponLog extends \dbbase\models\operation\coupon\CouponLog
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'order_id', 'coupon_id', 'coupon_code_id', 'coupon_log_type', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['coupon_price', 'coupon_log_price'], 'number'],
            [['coupon_log_type', 'coupon_log_type_name', 'coupon_log_price', 'created_at', 'updated_at'], 'required'],
            [['coupon_code', 'coupon_name', 'coupon_log_type_name'], 'string', 'max' => 255]
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
            'coupon_log_type' => Yii::t('coupon', '优惠券日志类型1为领取2为使用3为退还'),
            'coupon_log_type_name' => Yii::t('coupon', '优惠券日志类型名称'),
            'coupon_log_price' => Yii::t('coupon', '实际优惠或者退还金额'),
            'created_at' => Yii::t('coupon', '创建时间'),
            'updated_at' => Yii::t('coupon', '更新时间'),
            'is_del' => Yii::t('coupon', '是否逻辑删除'),
        ];
    }
}
