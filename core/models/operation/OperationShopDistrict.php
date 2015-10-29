<?php

namespace core\models\operation;

use Yii;
use dbbase\models\operation\OperationShopDistrict as CommonOperationShopDistrict;


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
class OperationShopDistrict extends CommonOperationShopDistrict
{
    /**
     * 上线商圈列表
     * @param type $city_id
     * @return type
     */
    public static function getCityShopDistrictList($city_id = ''){
        if(!empty($city_id)){
            return self::find()->asArray()->where(['operation_city_id' => $city_id])->all();
        }else{
            return self::find()->asArray()->all();
        }
    }

    public static function getShopDistrictName($shopdistrict_id){
        $data = self::find()->asArray()->where(['id' => $shopdistrict_id])->One();
        return $data['operation_shop_district_name'];
    }
    
    /**
     * 城市所属商圈数据
     * @param type $city_id
     * @return type
     */
    public static function getCityShopDistrictNum($city_id){
        $data = self::find()->asArray()->where(['operation_city_id' => $city_id])->all();
        return count($data);
    }
}
