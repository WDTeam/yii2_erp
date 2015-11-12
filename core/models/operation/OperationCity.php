<?php

namespace core\models\operation;

use Yii;

/**
 * This is the model class for table "{{%operation_city}}".
 *
 * @property integer $id
 * @property string $operation_city_name
 * @property integer $operation_city_is_online
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationCity extends \dbbase\models\operation\OperationCity
{
    public static function getOnlineCityList($online = 1){
        $data = self::find()->where(['operation_city_is_online' => $online])->all();
        $d = array();
        foreach((array)$data as $key => $value){
            $d[$value['city_id'].'-'.$value['city_name']] = $value['city_name'];
        }
        return $d;
    }

    public static function getOnlineCitys($online = 1){
        return  self::find()->where(['operation_city_is_online' => $online])->asArray()->all();
    }
    
    public static function getCityList(){
        $data = self::find()->all();
        $d = array();
        foreach((array)$data as $key => $value){
            $d[$value['city_id'].'-'.$value['city_name']] = $value['city_name'];
        }
        return $d;
    }

    public static function getCityName($city_id){
        $data = self::find()->select(['city_name'])->asArray()->where(['city_id' => $city_id])->One();
        return $data['city_name'];
    }
    
    public static function getCityInfo($city_id){
        return self::find()->where(['operation_city_is_online' => '1', 'city_id' => $city_id])->One();
    }
    
    /** 设置城市为开通状态**/
    public static function setoperation_city_is_online($cityid){
        return Yii::$app->db->createCommand()->update(self::tableName(), ['operation_city_is_online' => 1], ['city_id' => $cityid])->execute();
    }

    /**
     * 设置城市为下线状态
     */
    public static function setOperationCityIsOffline($city_id)
    {
        return Yii::$app->db->createCommand()->update(self::tableName(), ['operation_city_is_online' => 2], ['city_id' => $city_id])->execute();
    }
    
    /**查询开通城市列表**/
    public static function getCityOnlineInfoList(){
        return self::find()->asArray()->where(['operation_city_is_online' => 1])->all();
    }

    /**查询开通城市列表**/
    public static function getCityOnlineInfoListByProvince($province_id){
        return self::find()->asArray()->where(['province_id'=>$province_id,'operation_city_is_online' => 1])->all();
    }

    /**根据省份查询开通城市**/
    public static function getProvinceCityList(){
        return self::find()->where(['operation_city_is_online' => 1])->orderBy('province_id')->asArray()->all();
    }

    /**
     * 根据城市名称获取城市编号
     */
    public static function getCityId($city_name)
    {
        $data = self::find()->select(['city_id'])->asArray()->where(['city_name' => $city_name])->One();
        return $data['city_id'];
    }
    
}
