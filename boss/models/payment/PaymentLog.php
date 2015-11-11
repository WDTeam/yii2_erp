<?php

namespace boss\models\payment;

use Yii;

/**
 * This is the model class for table "{{%payment_log}}".
 *
 * @property integer $id
 * @property string $payment_log_price
 * @property string $payment_log_shop_name
 * @property string $payment_log_eo_order_id
 * @property string $payment_log_transaction_id
 * @property integer $payment_log_status_bool
 * @property string $payment_log_status
 * @property integer $pay_channel_id
 * @property string $pay_channel_name
 * @property string $payment_log_json_aggregation
 * @property integer $created_at
 * @property integer $updated_at
 */
class PaymentLog extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'payment_log';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id','id','payment_log_price', 'payment_log_status_bool', 'pay_channel_id', 'created_at', 'create_time','payment_log_json_aggregation',
             'payment_log_shop_name','payment_log_eo_order_id', 'payment_log_status','payment_log_transaction_id','pay_channel_name'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payment_log_price'], 'number'],
            [['payment_log_status_bool', 'pay_channel_id', 'created_at', 'updated_at'], 'integer'],
            [['payment_log_json_aggregation'], 'string'],
            [['payment_log_shop_name'], 'string', 'max' => 50],
            [['payment_log_eo_order_id', 'payment_log_status'], 'string', 'max' => 30],
            [['payment_log_transaction_id'], 'string', 'max' => 40],
            [['pay_channel_name'], 'string', 'max' => 20]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('PaymentLog', 'ID'),
            'payment_log_price' => Yii::t('PaymentLog', '支付金额'),
            'payment_log_shop_name' => Yii::t('PaymentLog', '商品名称'),
            'payment_log_eo_order_id' => Yii::t('PaymentLog', '第三方订单ID'),
            'payment_log_transaction_id' => Yii::t('PaymentLog', '第三方交易流水号'),
            'payment_log_status_bool' => Yii::t('PaymentLog', '状态数'),
            'payment_log_status' => Yii::t('PaymentLog', '状态'),
            'pay_channel_id' => Yii::t('PaymentLog', '支付渠道'),
            'pay_channel_name' => Yii::t('PaymentLog', '支付渠道名称'),
            'payment_log_json_aggregation' => Yii::t('PaymentLog', '记录数据集合'),
            'created_at' => Yii::t('PaymentLog', '创建时间'),
            'create_time' => Yii::t('PaymentLog', '创建时间'),
        ];
    }
}
