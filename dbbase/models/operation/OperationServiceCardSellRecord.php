<?php

namespace dbbase\models\operation;


/**
 * This is the model class for table "ejj_operation_service_card_sell_record".
 *
 * @property string $id
 * @property string $service_card_sell_record_code
 * @property string $customer_id
 * @property integer $customer_phone
 * @property string $service_card_info_id
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
class OperationServiceCardSellRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'customer_phone', 'service_card_info_id', 'service_card_sell_record_channel_id', 'service_card_sell_record_status', 'customer_trans_record_pay_mode', 'pay_channel_id', 'customer_trans_record_pay_account', 'customer_trans_record_paid_at', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['service_card_sell_record_money', 'customer_trans_record_pay_money'], 'number'],
            [['service_card_sell_record_code'], 'string', 'max' => 20],
            [['service_card_info_name', 'service_card_sell_record_channel_name', 'customer_trans_record_pay_channel'], 'string', 'max' => 64],
            [['customer_trans_record_transaction_id'], 'string', 'max' => 255]
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_service_card_sell_record}}';
    }

       /**
     * @introduction 基于购卡订单号更记录
     */
    public function updateByCode($code){
       // $this->update()->where(['service_card_sell_record_code'=>$code]);
        $this->updateAll(['customer_trans_record_pay_money'],'service_card_sell_record_code=:service_card_sell_record_code',
            [':service_card_sell_record_code'=>$code]);


    }
}
