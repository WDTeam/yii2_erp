<?php

namespace common\models\operation;


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
class OperationServiceCardSellRecord extends \yii\db\ActiveRecord
{
     public  $id;
     public  $service_card_sell_record_code;
     public  $customer_id;
     public  $customer_phone;
     public  $service_card_info_card_id;
     public  $service_card_info_name;
     public  $service_card_sell_record_money;
     public  $service_card_sell_record_channel_id;
     public  $service_card_sell_record_channel_name;
     public  $service_card_sell_record_status;
     public  $customer_trans_record_pay_mode;
     public  $pay_channel_id;
     public  $customer_trans_record_pay_channel;
     public  $customer_trans_record_transaction_id;
     public  $customer_trans_record_pay_money;
     public  $customer_trans_record_pay_account;
     public  $customer_trans_record_paid_at;
     public  $created_at;
     public  $updated_at;
     public  $is_del;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_service_card_sell_record}}';
    }

    /**
     * 保存服务卡销售记录
     * @return bool
     */
    public function doSave(){
        if ($this->save()) {
            return true;
        }
        return false;
    }
}
