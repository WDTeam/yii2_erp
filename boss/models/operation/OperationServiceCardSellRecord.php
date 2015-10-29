<?php

namespace boss\models\operation;

use Yii;

/**
 * This is the model class for table "ejj_operation_service_card_sell_record".
 *
 * @property string $id
 * @property string $service_card_sell_record_code
 * @property string $customer_id
 * @property integer $customer_phone
 * @property string $service_card_info_card_id
 * @property string $service_card_info_name
 * @property string $service_card_sell_record_money
 * @property integer $service_card_sell_record_channel_id
 * @property string $service_card_sell_record_channel_name
 * @property integer $service_card_sell_record_status
 * @property integer $customer_trans_record_pay_mode
 * @property integer $pay_channel_id
 * @property string $customer_trans_record_pay_channel
 * @property string $customer_trans_record_transaction_id
 * @property string $customer_trans_record_pay_money
 * @property integer $customer_trans_record_pay_account
 * @property integer $customer_trans_record_paid_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class OperationServiceCardSellRecord extends \core\models\operation\OperationServiceCardSellRecord
{
    
	public $customer_trans_record_paid_at_min;
	public $customer_trans_record_paid_at_max;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'customer_phone', 'service_card_info_card_id', 'service_card_sell_record_channel_id', 'service_card_sell_record_status', 'customer_trans_record_pay_mode', 'pay_channel_id', 'customer_trans_record_pay_account', 'customer_trans_record_paid_at', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['service_card_sell_record_money', 'customer_trans_record_pay_money'], 'number'],
            [['service_card_sell_record_code'], 'string', 'max' => 20],
            [['service_card_info_name', 'service_card_sell_record_channel_name', 'customer_trans_record_pay_channel'], 'string', 'max' => 64],
            [['customer_trans_record_transaction_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'service_card_sell_record_code' => Yii::t('app', '购卡订单号'),
            'customer_id' => Yii::t('app', '用户id'),
            'customer_phone' => Yii::t('app', '用户手机号'),
            'service_card_info_card_id' => Yii::t('app', '服务卡id'),
            'service_card_info_name' => Yii::t('app', '服务卡名'),
            'service_card_sell_record_money' => Yii::t('app', '购卡订单金额'),
            'service_card_sell_record_channel_id' => Yii::t('app', '购卡订单渠道id'),
            'service_card_sell_record_channel_name' => Yii::t('app', '购卡订单渠道名称'),
            'service_card_sell_record_status' => Yii::t('app', '购卡订单状态'),
            'customer_trans_record_pay_mode' => Yii::t('app', '支付方式'),
            'pay_channel_id' => Yii::t('app', '支付渠道id'),
            'customer_trans_record_pay_channel' => Yii::t('app', '支付渠道名称'),
            'customer_trans_record_transaction_id' => Yii::t('app', '支付流水号'),
            'customer_trans_record_pay_money' => Yii::t('app', '支付金额'),
            'customer_trans_record_pay_account' => Yii::t('app', '支付帐号'),
            'customer_trans_record_paid_at' => Yii::t('app', '支付时间'),
			'customer_trans_record_paid_at_min' => Yii::t('app', '支付时间'),
			'customer_trans_record_paid_at_max' => Yii::t('app', ''),
            'created_at' => Yii::t('app', '订单创建时间'),
            'updated_at' => Yii::t('app', '订单更改时间'),
            'is_del' => Yii::t('app', '状态'),
        ];
    }
}
