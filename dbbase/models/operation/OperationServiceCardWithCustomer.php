<?php

namespace dbbase\models\operation;


/**
 * This is the model class for table "ejj_operation_service_card_with_customer".
 *
 * @property string $id
 * @property string $service_card_sell_record_id
 * @property string $service_card_sell_record_code
 * @property string $service_card_info_id
 * @property string $service_card_with_customer_code
 * @property string $service_card_info_name
 * @property string $customer_trans_record_pay_money
 * @property string $service_card_info_value
 * @property string $service_card_info_rebate_value
 * @property string $service_card_with_customer_balance
 * @property integer $customer_id
 * @property string $customer_phone
 * @property integer $service_card_info_scope
 * @property integer $service_card_with_customer_buy_at
 * @property integer $service_card_with_customer_valid_at
 * @property integer $service_card_with_customer_activated_at
 * @property integer $service_card_with_customer_status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class OperationServiceCardWithCustomer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_service_card_with_customer}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['service_card_sell_record_id', 'service_card_info_id', 'customer_id', 'service_card_info_scope', 'service_card_with_customer_buy_at', 'service_card_with_customer_valid_at', 'service_card_with_customer_activated_at', 'service_card_with_customer_status', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['customer_trans_record_pay_money', 'service_card_info_value', 'service_card_info_rebate_value', 'service_card_with_customer_balance'], 'number'],
            [['service_card_sell_record_code', 'service_card_with_customer_code', 'service_card_info_name'], 'string', 'max' => 64],
            [['customer_phone'], 'string', 'max' => 11]
        ];
    }
}
