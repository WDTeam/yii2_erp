<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%general_pay_log}}".
 *
 * @property integer $id
 * @property string $general_pay_log_price
 * @property string $general_pay_log_shop_name
 * @property string $general_pay_log_eo_order_id
 * @property string $general_pay_log_transaction_id
 * @property string $general_pay_log_status
 * @property integer $pay_channel_id
 * @property string $general_pay_log_json_aggregation
 * @property string $created_at
 * @property string $updated_at
 * @property integer $is_del
 */
class GeneralPayLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%general_pay_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['general_pay_log_price', 'general_pay_log_eo_order_id', 'general_pay_log_transaction_id', 'general_pay_log_status', 'general_pay_log_json_aggregation', 'created_at', 'updated_at'], 'required'],
            [['general_pay_log_price'], 'number'],
            [['pay_channel_id', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['general_pay_log_json_aggregation'], 'string'],
            [['general_pay_log_shop_name'], 'string', 'max' => 50],
            [['general_pay_log_eo_order_id', 'general_pay_log_status'], 'string', 'max' => 30],
            [['general_pay_log_transaction_id'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'general_pay_log_price' => Yii::t('app', '支付金额'),
            'general_pay_log_shop_name' => Yii::t('app', '商品名称'),
            'general_pay_log_eo_order_id' => Yii::t('app', '第三方订单ID'),
            'general_pay_log_transaction_id' => Yii::t('app', '第三方交易流水号'),
            'general_pay_log_status' => Yii::t('app', '状态'),
            'pay_channel_id' => Yii::t('app', '支付渠道'),
            'general_pay_log_json_aggregation' => Yii::t('app', '记录数据集合'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
            'is_del' => Yii::t('app', '删除'),
        ];
    }
}
