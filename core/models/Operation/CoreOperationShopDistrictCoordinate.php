<?php

namespace core\models\Operation;

use Yii;
use common\models\Operation\CommonOperationShopDistrictCoordinate;

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
class CoreOperationShopDistrictCoordinate extends CommonOperationShopDistrictCoordinate
{
    public static function setShopDistrictCoordinate($coordinate){
        $fields = ['operation_shop_district_id', 'operation_shop_district_name', 'operation_city_id', 'operation_city_name', 'operation_shop_district_coordinate_start_longitude', 'operation_shop_district_coordinate_start_latitude', 'operation_shop_district_coordinate_end_longitude', 'operation_shop_district_coordinate_end_latitude', 'created_at', 'updated_at'];
        $data[] = $coordinate;
        Yii::$app->db->createCommand()->batchInsert(self::tableName(), $fields, $data)->execute();
    }
    
    public static function upShopDistrictCoordinate($coordinate, $operation_shop_district_id){
        Yii::$app->db->createCommand()->update(self::tableName(), $coordinate, ['operation_shop_district_id' => $operation_shop_district_id])->execute();
    }
    
    public static function getShopDistrictCoordinate($operation_shop_district_id){
        return self::find()->asArray()->where(['operation_shop_district_id' => $operation_shop_district_id])->One();
    }

}
