<?php
namespace common\models\operation;
use Yii;

/**
 * This is the model class for table "{{%operation_boot_page_city}}".
 *
 * @property integer $id
 * @property integer $operation_boot_page_id
 * @property integer $operation_city_id
 * @property string $operation_city_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class CommonOperationBootPageCity extends \yii\db\ActiveRecord
{/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_boot_page_city}}';
    }
    
}
