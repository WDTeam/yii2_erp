<?php

namespace common\models\operation;

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
class OperationServerCardRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_server_card_record}}';
    }

}
