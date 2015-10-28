<?php

namespace boss\models\operation;

use Yii;

/**
 * This is the model class for table "{{%operation_server_card_customer}}".
 *
 * @property string $id
 * @property string $order_id
 * @property string $order_code
 * @property string $card_id
 * @property string $card_no
 * @property string $card_name
 * @property integer $card_type
 * @property integer $card_level
 * @property string $pay_value
 * @property string $par_value
 * @property string $reb_value
 * @property string $res_value
 * @property integer $customer_id
 * @property string $customer_name
 * @property string $customer_phone
 * @property integer $use_scope
 * @property integer $buy_at
 * @property integer $valid_at
 * @property integer $activated_at
 * @property integer $freeze_flag
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationServerCardCustomer extends \core\models\operation\OperationServerCardCustomer
{
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'card_id', 'card_type', 'card_level', 'customer_id', 'use_scope', 'buy_at', 'valid_at', 'activated_at', 'freeze_flag', 'created_at', 'updated_at'], 'integer'],
            [['pay_value', 'par_value', 'reb_value', 'res_value'], 'number'],
            [['order_code', 'card_no', 'card_name'], 'string', 'max' => 64],
            [['customer_name'], 'string', 'max' => 16],
            [['customer_phone'], 'string', 'max' => 11]
        ];
    }

   
}
