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
    public static function getOnlineCityList($online = 1){
        $data = self::find()->where(['operation_city_is_online' => $online])->all();
        $d = array();
        foreach((array)$data as $key => $value){
            $d[$value['city_id'].'-'.$value['city_name']] = $value['city_name'];
        }
        return $d;
    }
    
    /**
     * 获取城市名称
     * @param type $city_id
     * @return type
     */
    public static function getCityName($city_id){
        $data = self::find()->select(['city_name'])->asArray()->where(['operation_city_is_online' => '1', 'city_id' => $city_id])->One();
        return $data['city_name'];
    }
    
    public static function getCityInfo($city_id){
        return self::find()->where(['operation_city_is_online' => '1', 'city_id' => $city_id])->One();
    }
    
    /** 设置城市为开通状态**/
    public static function setoperation_city_is_online($cityid){
        return Yii::$app->db->createCommand()->update(self::tableName(), ['operation_city_is_online' => 1], ['city_id' => $cityid])->execute();
    }
}