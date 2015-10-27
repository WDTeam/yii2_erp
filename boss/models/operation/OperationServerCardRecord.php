<?php

namespace boss\models\operation;

use Yii;

/**
 * This is the model class for table "{{%operation_server_card_record}}".
 *
 * @property string $id
 * @property string $trade_id
 * @property string $cus_card_id
 * @property string $front_value
 * @property string $behind_value
 * @property string $use_value
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationServerCardRecord extends \core\models\operation\OperationServerCardRecord
{
  
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['trade_id', 'cus_card_id', 'created_at', 'updated_at'], 'integer'],
            [['front_value', 'behind_value', 'use_value'], 'number']
        ];
    }

    
}
