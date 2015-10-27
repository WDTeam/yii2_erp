<?php
namespace boss\models\operation;
use Yii;
use core\models\operation\OperationBootPageCity as CoreOperationBootPageCity;

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
class OperationBootPageCity extends CoreOperationBootPageCity
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        
    }
}
