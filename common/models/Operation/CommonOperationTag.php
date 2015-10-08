<?php

namespace common\models\Operation;

use Yii;

/**
 * This is the model class for table "{{%operation_tag}}".
 *
 * @property integer $id
 * @property string $operation_tag_name
 * @property integer $operation_applicable_scope_id
 * @property string $operation_applicable_scope_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class CommonOperationTag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_tag}}';
    }
}
