<?php

namespace common\models\operation;

use Yii;

/**
 * This is the model class for table "{{%operation_platform_version}}".
 *
 * @property integer $id
 * @property integer $operation_platform_id
 * @property string $operation_platform_name
 * @property string $operation_platform_version_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationPlatformVersion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_platform_version}}';
    }
}
