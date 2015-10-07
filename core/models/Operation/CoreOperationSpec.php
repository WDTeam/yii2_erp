<?php

namespace core\models\Operation;

use Yii;
use common\models\Operation\CommonOperationSpec;

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
class CoreOperationSpec extends CommonOperationSpec
{
    public static function hanldeSpecValues($operation_spec_values){
        return implode('          ', unserialize($operation_spec_values));
    }

    public static function getSpecList(){
        return self::find()->All();
    }
}
