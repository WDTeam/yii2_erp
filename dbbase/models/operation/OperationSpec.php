<?php

namespace dbbase\models\operation;

use Yii;


/**
 * This is the model class for table "{{%operation_spec}}".
 *
 * @property integer $id
 * @property string $operation_spec_name
 * @property string $operation_spec_description
 * @property string $operation_spec_values
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationSpec extends \yii\db\ActiveRecord
{
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_spec}}';
    }
}
