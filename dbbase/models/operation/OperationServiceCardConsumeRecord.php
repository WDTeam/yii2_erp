<?php

namespace dbbase\models\operation;


/**
 * This is the model class for table "{{%operation_service_card_consume_record}}".
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
class OperationServiceCardConsumeRecord extends \yii\db\ActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_service_card_consume_record}}';
    }
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
}
