<?php

namespace core\models\Operation;

use Yii;
use common\models\Operation\CommonOperationShopDistrict;

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
class CoreOperationShopDistrict extends CommonOperationShopDistrict
{
    public static function getCityShopDistrictList($city_id){
        return self::find()->asArray()->where(['operation_city_id' => $city_id])->all();
    }
}
