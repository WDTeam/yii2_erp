<?php

namespace common\models\operation;

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
class OperationServerCard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_server_card}}';
    }
	
	
}
