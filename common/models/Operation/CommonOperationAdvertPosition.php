<?php

namespace common\models\Operation;

use Yii;

/**
 * This is the model class for table "{{%operation_advert_position}}".
 *
 * @property integer $id
 * @property string $operation_advert_position_name
 * @property integer $operation_platform_id
 * @property string $operation_platform_name
 * @property integer $operation_platform_version_id
 * @property string $operation_platform_version_name
 * @property integer $operation_city_id
 * @property string $operation_city_name
 * @property integer $operation_advert_position_width
 * @property integer $operation_advert_position_height
 * @property integer $created_at
 * @property integer $updated_at
 */
class CommonOperationAdvertPosition extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_advert_position}}';
    }
}
