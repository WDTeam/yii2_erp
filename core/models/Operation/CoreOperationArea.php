<?php

namespace core\models\Operation;

use Yii;
use common\models\Operation\CommonOperationArea;

/**
 * This is the model class for table "{{%operation_area}}".
 *
 * @property integer $id
 * @property string $area_name
 * @property integer $parent_id
 * @property string $short_name
 * @property string $longitude
 * @property string $latitude
 * @property integer $level
 * @property string $position
 * @property integer $sort
 */
class CoreOperationArea extends CommonOperationArea
{
    public static function getAreaList($parent_id){
        $data = self::find()->select(['id', 'area_name'])->asArray()->where(['parent_id' => $parent_id])->All();
        $d = array();
        foreach((array)$data as $key => $value){
            $d[$value['id'].'_'.$value['area_name']] = $value['area_name'];
        }
        return $d;
    }
}
