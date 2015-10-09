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
        if(!empty($operation_spec_values)){
            return implode('          ', unserialize($operation_spec_values));
        }else{
            return '';
        }
    }

    public static function getSpecList(){
        $data = self::find()->select(['id', 'operation_spec_name'])->asArray()->All();
        $d = array();
        foreach((array)$data as $key => $value){
            $d[$value['id']] = $value['operation_spec_name'];
        }
        return $d;
    }
    
    public static function getSpecInfo($spec_id){
        return self::find()->asArray()->where(['id' => $spec_id])->One();
    }
}
