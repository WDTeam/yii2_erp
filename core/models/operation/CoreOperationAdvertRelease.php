<?php

namespace core\models\operation;

use Yii;
use common\models\operation\CommonOperationAdvertRelease;
/**
 * This is the model class for table "{{%operation_advert_release}}".
 *
 * @property integer $id
 * @property string $operation_advert_position_id
 * @property string $operation_advert_position_name
 * @property integer $operation_advert_content_id
 * @property integer $operation_advert_content_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class CoreOperationAdvertRelease extends CommonOperationAdvertRelease
{
    public function getAdvertList($city_id, $platform_id = 0, $version_id = 0, $position_id = 0){
        $data = CoreOperationAdvertRelease::find()->asArray()->where(['city_id' => $city_id])->all();
        $d = array();
        $ids = array();
        foreach ((array)$data as $key => $value){
            $contents = unserialize($value['operation_release_contents']);
            array_push($d, $contents);
            array_push($ids, $contents['id'][0]);
        }
        $adverts = self::getAdvertListFrom($ids, $platform_id, $version_id, $position_id);
        return $adverts;
    }
    
    static private function getAdvertListFrom($ids, $platform_id, $version_id, $position_id){
//        foreach($contents)
//        $adverts = $this->getAdvertListFromPosition($position_id);
    }
}
