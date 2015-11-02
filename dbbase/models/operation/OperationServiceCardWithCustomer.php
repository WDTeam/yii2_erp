<?php

namespace common\models\operation;


/**
 * This is the model class for table "ejj_operation_service_card_with_customer".
 *
 * @property string $id
 * @property string $service_card_sell_record_id
 * @property string $service_card_sell_record_code
 * @property string $server_card_info_id
 * @property string $service_card_with_customer_code
 * @property string $server_card_info_name
 * @property string $customer_trans_record_pay_money
 * @property string $server_card_info_value
 * @property string $service_card_info_rebate_value
 * @property string $service_card_with_customer_balance
 * @property integer $customer_id
 * @property string $customer_phone
 * @property integer $server_card_info_scope
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
    public  $id;
    public  $service_card_sell_record_id;
    public  $service_card_sell_record_code;
    public  $server_card_info_id;
    public  $service_card_with_customer_code;
    public  $server_card_info_name;
    public  $customer_trans_record_pay_money;
    public  $server_card_info_value;
    public  $service_card_info_rebate_value;
    public  $service_card_with_customer_balance;
    public  $customer_id;
    public  $customer_phone;
    public  $server_card_info_scope;
    public  $service_card_with_customer_buy_at;
    public  $service_card_with_customer_valid_at;
    public  $service_card_with_customer_activated_at;
    public  $service_card_with_customer_status;
    public  $created_at;
    public  $updated_at;
    public  $is_del;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_service_card_with_customer}}';
    }

}
