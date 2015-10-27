<?php

namespace boss\models\operation;

use Yii;

/**
 * This is the model class for table "{{%operation_server_card}}".
 *
 * @property string $id
 * @property string $card_name
 * @property integer $card_type
 * @property integer $card_level
 * @property string $par_value
 * @property string $reb_value
 * @property integer $use_scope
 * @property integer $valid_days
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationServerCard extends \core\models\operation\OperationServerCard
{
    
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['card_type', 'card_level', 'use_scope', 'valid_days', 'created_at', 'updated_at'], 'required'],
            [['card_type', 'card_level', 'use_scope', 'valid_days', 'created_at', 'updated_at'], 'integer'],
            [['par_value', 'reb_value'], 'number'],
            [['card_name'], 'string', 'max' => 64]
        ];
    }

   
   
}
