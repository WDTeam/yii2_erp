<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%customer_trans_record}}".
 *
 * @property integer $id
 * @property string $customer_id
 * @property string $order_id
 * @property integer $order_channel_id
 * @property integer $customer_trans_record_order_channel
 * @property integer $pay_channel_id
 * @property integer $customer_trans_record_pay_channel
 * @property integer $customer_trans_record_mode
 * @property integer $customer_trans_record_mode_name
 * @property string $customer_trans_record_promo_code_money
 * @property string $customer_trans_record_coupon_money
 * @property string $customer_trans_record_cash
 * @property string $customer_trans_record_pre_pay
 * @property string $customer_trans_record_online_pay
 * @property string $customer_trans_record_online_balance_pay
 * @property string $customer_trans_record_online_service_card_on
 * @property string $customer_trans_record_online_service_card_pay
 * @property string $customer_trans_record_refund_money
 * @property string $customer_trans_record_money
 * @property string $customer_trans_record_order_total_money
 * @property string $customer_trans_record_total_money
 * @property string $customer_trans_record_current_balance
 * @property string $customer_trans_record_befor_balance
 * @property string $customer_trans_record_transaction_id
 * @property string $customer_trans_record_remark
 * @property string $customer_trans_record_verify
 * @property string $created_at
 * @property string $updated_at
 * @property integer $is_del
 */
class CustomerTransRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_trans_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'customer_trans_record_order_channel', 'pay_channel_id', 'customer_trans_record_pay_channel', 'customer_trans_record_mode_name', 'customer_trans_record_refund_money', 'customer_trans_record_verify'], 'required'],
            [['customer_id', 'order_id', 'order_channel_id', 'customer_trans_record_order_channel', 'pay_channel_id', 'customer_trans_record_pay_channel', 'customer_trans_record_mode', 'customer_trans_record_mode_name', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['customer_trans_record_promo_code_money', 'customer_trans_record_coupon_money', 'customer_trans_record_cash', 'customer_trans_record_pre_pay', 'customer_trans_record_online_pay', 'customer_trans_record_online_balance_pay', 'customer_trans_record_online_service_card_pay', 'customer_trans_record_refund_money', 'customer_trans_record_money', 'customer_trans_record_order_total_money', 'customer_trans_record_total_money', 'customer_trans_record_current_balance', 'customer_trans_record_befor_balance'], 'number'],
            [['customer_trans_record_online_service_card_on'], 'string', 'max' => 30],
            [['customer_trans_record_transaction_id'], 'string', 'max' => 40],
            [['customer_trans_record_remark'], 'string', 'max' => 255],
            [['customer_trans_record_verify'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'customer_id' => Yii::t('app', '用户ID'),
            'order_id' => Yii::t('app', '订单ID'),
            'order_channel_id' => Yii::t('app', '订单渠道'),
            'customer_trans_record_order_channel' => Yii::t('app', '订单渠道名称'),
            'pay_channel_id' => Yii::t('app', '支付渠道'),
            'customer_trans_record_pay_channel' => Yii::t('app', '支付渠道名称'),
            'customer_trans_record_mode' => Yii::t('app', '交易方式:1消费,2=充值,3=退款,4=赔偿,5=补偿'),
            'customer_trans_record_mode_name' => Yii::t('app', '交易方式名称'),
            'customer_trans_record_promo_code_money' => Yii::t('app', '优惠码金额'),
            'customer_trans_record_coupon_money' => Yii::t('app', '优惠券金额'),
            'customer_trans_record_cash' => Yii::t('app', '现金支付'),
            'customer_trans_record_pre_pay' => Yii::t('app', '预付费金额（第三方）'),
            'customer_trans_record_online_pay' => Yii::t('app', '在线支付'),
            'customer_trans_record_online_balance_pay' => Yii::t('app', '在线余额支付'),
            'customer_trans_record_online_service_card_on' => Yii::t('app', '服务卡号'),
            'customer_trans_record_online_service_card_pay' => Yii::t('app', '服务卡支付'),
            'customer_trans_record_refund_money' => Yii::t('app', '退款金额'),
            'customer_trans_record_money' => Yii::t('app', '余额支付'),
            'customer_trans_record_order_total_money' => Yii::t('app', '订单总额'),
            'customer_trans_record_total_money' => Yii::t('app', '交易总额'),
            'customer_trans_record_current_balance' => Yii::t('app', '当前余额'),
            'customer_trans_record_befor_balance' => Yii::t('app', '之前余额'),
            'customer_trans_record_transaction_id' => Yii::t('app', '交易流水号'),
            'customer_trans_record_remark' => Yii::t('app', '备注'),
            'customer_trans_record_verify' => Yii::t('app', '验证'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
            'is_del' => Yii::t('app', '删除'),
        ];
    }
}