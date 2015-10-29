<?php

namespace boss\models\operation;

use Yii;

/**
 * This is the model class for table "ejj_operation_service_card_consume_record".
 *
 * @property string $id
 * @property string $customer_id
 * @property string $customer_trans_record_transaction_id
 * @property string $order_id
 * @property string $order_code
 * @property string $service_card_with_customer_id
 * @property string $service_card_with_customer_code
 * @property string $service_card_consume_record_front_money
 * @property string $service_card_consume_record_behind_money
 * @property integer $service_card_consume_record_consume_type
 * @property integer $service_card_consume_record_business_type
 * @property string $service_card_consume_record_use_money
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class OperationServiceCardConsumeRecord extends \core\models\operation\OperationServiceCardConsumeRecord
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'customer_trans_record_transaction_id', 'order_id', 'service_card_with_customer_id', 'service_card_consume_record_consume_type', 'service_card_consume_record_business_type', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['service_card_consume_record_front_money', 'service_card_consume_record_behind_money', 'service_card_consume_record_use_money'], 'number'],
            [['order_code'], 'string', 'max' => 20],
            [['service_card_with_customer_code'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'customer_id' => Yii::t('app', '用户id'),
            'customer_trans_record_transaction_id' => Yii::t('app', '服务交易流水'),
            'order_id' => Yii::t('app', '服务订单id'),
            'order_code' => Yii::t('app', '服务订单号'),
            'service_card_with_customer_id' => Yii::t('app', '服务卡id'),
            'service_card_with_customer_code' => Yii::t('app', '服务卡号'),
            'service_card_consume_record_front_money' => Yii::t('app', '使用前金额'),
            'service_card_consume_record_behind_money' => Yii::t('app', '使用后金额'),
            'service_card_consume_record_consume_type' => Yii::t('app', '服务类型'),
            'service_card_consume_record_business_type' => Yii::t('app', '业务类型'),
            'service_card_consume_record_use_money' => Yii::t('app', '使用金额'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更改时间'),
            'is_del' => Yii::t('app', '状态'),
        ];
    }
}
