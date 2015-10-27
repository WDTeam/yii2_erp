<?php

namespace boss\models\operation;

use Yii;

/**
 * This is the model class for table "{{%operation_server_card_order}}".
 *
 * @property string $id
 * @property string $order_code
 * @property string $usere_id
 * @property integer $order_customer_phone
 * @property string $server_card_id
 * @property string $card_name
 * @property integer $card_type
 * @property integer $card_level
 * @property string $par_value
 * @property string $reb_value
 * @property string $order_money
 * @property integer $order_src_id
 * @property string $order_src_name
 * @property integer $order_channel_id
 * @property string $order_channel_name
 * @property integer $order_lock_status
 * @property integer $order_status_id
 * @property string $order_status_name
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $order_pay_type
 * @property integer $pay_channel_id
 * @property string $pay_channel_name
 * @property string $order_pay_flow_num
 * @property string $order_pay_money
 * @property integer $paid_at
 */
class OperationServerCardOrder extends \core\models\operation\OperationServerCardOrder
{
   

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['usere_id', 'order_customer_phone', 'server_card_id', 'card_type', 'card_level', 'order_src_id', 'order_channel_id', 'order_lock_status', 'order_status_id', 'created_at', 'updated_at', 'order_pay_type', 'pay_channel_id', 'paid_at'], 'integer'],
            [['par_value', 'reb_value', 'order_money', 'order_pay_money'], 'number'],
            [['order_code'], 'string', 'max' => 20],
            [['card_name', 'order_src_name', 'order_channel_name', 'order_status_name', 'pay_channel_name'], 'string', 'max' => 64],
            [['order_pay_flow_num'], 'string', 'max' => 255]
        ];
    }

    
}
