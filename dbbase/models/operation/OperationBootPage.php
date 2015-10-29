<?php
namespace common\models\operation;

use Yii;

/**
 * This is the model class for table "{{%operation_boot_page}}".
 *
 * @property integer $id
 * @property string $operation_boot_page_name
 * @property string $operation_boot_page_ios_img
 * @property string $operation_boot_page_android_img
 * @property string $operation_boot_page_url
 * @property integer $operation_boot_page_residence_time
 * @property integer $operation_boot_page_online_time
 * @property integer $operation_boot_page_offline_time
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationBootPage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_boot_page}}';
    }


}
