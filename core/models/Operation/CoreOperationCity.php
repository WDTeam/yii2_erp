<?php

namespace core\models\Operation;

use Yii;
use common\models\Operation\CommonOperationCity;

/**
 * This is the model class for table "{{%operation_city}}".
 *
 * @property integer $id
 * @property string $operation_city_name
 * @property integer $operation_city_is_online
 * @property integer $created_at
 * @property integer $updated_at
 */
class CoreOperationCity extends CommonOperationCity
{
    public static function getOnlineCityList(){
        $data = self::find()->where(['operation_city_is_online' => '1'])->all();
        $d = array();
        foreach((array)$data as $key => $value){
            $d[$value['city_id'].'-'.$value['city_name']] = $value['city_name'];
        }
        return $d;
    }
}
