<?php

namespace dbbase\models\operation;

use Yii;

/**
 * This is the model class for table "{{%operation_platform}}".
 *
 * @property integer $id
 * @property string $operation_platform_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationPlatform extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_platform}}';
    }
}
