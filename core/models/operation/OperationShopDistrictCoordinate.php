<?php

namespace core\models\operation;

use Yii;

/**
 * This is the model class for table "{{%operation_price_strategy}}".
 *
 * @property integer $id
 * @property string $operation_price_strategy_name
 * @property string $operation_price_strategy_unit
 * @property string $operation_price_strategy_lowest_consume_unit
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationShopDistrictCoordinate extends \dbbase\models\operation\OperationShopDistrictCoordinate
{
    public static function setShopDistrictCoordinate($coordinate){
        $fields = ['operation_shop_district_id', 'operation_shop_district_name', 'operation_city_id', 'operation_city_name', 'operation_shop_district_coordinate_start_longitude', 'operation_shop_district_coordinate_start_latitude', 'operation_shop_district_coordinate_end_longitude', 'operation_shop_district_coordinate_end_latitude', 'operation_area_id', 'operation_area_name', 'created_at', 'updated_at'];
        $data[] = $coordinate;
        Yii::$app->db->createCommand()->batchInsert(self::tableName(), $fields, $data)->execute();
    }
    
    public static function upShopDistrictCoordinate($coordinate, $operation_shop_district_id){
        Yii::$app->db->createCommand()->update(self::tableName(), $coordinate, ['operation_shop_district_id' => $operation_shop_district_id])->execute();
    }
    
    public static function settingShopDistrictCoordinate($coordinateinfo){

        if (!empty($coordinateinfo['operation_shop_district_id'])) {
            self::deleteAll(['operation_shop_district_id' => $coordinateinfo['operation_shop_district_id']]);
            $fields = [
                'operation_shop_district_id',
                'operation_shop_district_name',
                'operation_city_id',
                'operation_city_name',
                'operation_shop_district_coordinate_start_longitude',
                'operation_shop_district_coordinate_start_latitude',
                'operation_shop_district_coordinate_end_longitude',
                'operation_shop_district_coordinate_end_latitude',
                'operation_area_id',
                'operation_area_name',
                'created_at',
                'updated_at'
            ];
            $data = [];
            $i = 0;
            foreach ((array)
                $coordinateinfo['operation_shop_district_coordinate_start_longitude'] 
                as $key => $value) {
                $data[$i][] = $coordinateinfo['operation_shop_district_id'];
                $data[$i][] = $coordinateinfo['operation_shop_district_name'];
                $data[$i][] = $coordinateinfo['operation_city_id'];
                $data[$i][] = $coordinateinfo['operation_city_name'];
                $data[$i][] = $value;
                $data[$i][] = $coordinateinfo['operation_shop_district_coordinate_start_latitude'][$key];
                $data[$i][] = $coordinateinfo['operation_shop_district_coordinate_end_longitude'][$key];
                $data[$i][] = $coordinateinfo['operation_shop_district_coordinate_end_latitude'][$key];
                $data[$i][] = $coordinateinfo['operation_area_id'];
                $data[$i][] = $coordinateinfo['operation_area_name'];
                $data[$i][] = time();
                $data[$i][] = time();
                $i++;
            }
            Yii::$app->db->createCommand()->batchInsert(self::tableName(), $fields, $data)->execute();
        }
    }
    
    public static function getShopDistrictCoordinate($operation_shop_district_id){
        return self::find()->asArray()->where(['operation_shop_district_id' => $operation_shop_district_id])->All();
    }

    /**
     * 根据经纬度查询所属商圈，返回商圈
     *
     * @param type $longitude
     * @param type $latitude
     * @return type
     */
    public static function getCoordinateShopDistrictInfo($longitude, $latitude){

        //$where = '`operation_shop_district_coordinate_start_longitude` >= '.$longitude.' AND '.$longitude.' <= `operation_shop_district_coordinate_end_longitude`'.' AND `operation_shop_district_coordinate_start_latitude` >= '.$latitude.' AND `operation_shop_district_coordinate_end_latitude` <= '.$latitude;
        $where = '`operation_shop_district_coordinate_start_longitude` <= '.$longitude.' AND '.$longitude.' <= `operation_shop_district_coordinate_end_longitude`'.' AND `operation_shop_district_coordinate_start_latitude` >= '.$latitude.' AND `operation_shop_district_coordinate_end_latitude` <='.$latitude;
        return self::find()->select(['id', 'operation_shop_district_id', 'operation_shop_district_name', 'operation_city_id', 'operation_city_name', 'operation_area_id', 'operation_area_name'])->asArray()->where($where)->one();
    }

    /**
     * 删除商圈经纬度
     *
     * @param inter $operation_shop_district_id    商圈id
     */
    public static function delCoordinateInfo($operation_shop_district_id)
    {
        self::deleteAll(['operation_shop_district_id' => $operation_shop_district_id]);
    }
}
