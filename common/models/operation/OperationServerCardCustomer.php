<?php

namespace common\models\operation;

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
class OperationServerCardCustomer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_server_card_customer}}';
    }
	
	

   
}
