<?php

namespace core\models\operation;

use Yii;
use dbbase\models\operation\OperationShopDistrictCoordinate;


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
class OperationShopDistrict extends \dbbase\models\operation\OperationShopDistrict
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

    /**
     * 批量上传商圈信息
     *
     * @param array $district_arr    商圈信息
     */
    public static function saveBatchDistrictData($city_id, $city_name, $district_arr)
    {
        foreach ($district_arr as $keys => $values) {

            foreach ($values['district'] as $k => $v) {

                //插入商圈名称信息
                $model = new OperationShopDistrict();

                $model->operation_shop_district_name = $v['operation_shop_district_name'];
                $model->operation_city_id = $city_id;
                $model->operation_city_name = $city_name;
                $model->operation_area_id = $values['operation_area_id'];
                $model->operation_area_name = $values['operation_area_name'];

                $model->insert();
                $insert_id = $model->id;

                //插入商圈经纬度信息
                if (isset($v['l_n_t'])) {
                    $len = count($v['l_n_t']);
                    for ($i = 0; $i < $len; $i ++) {

                        $coordinateModel = new OperationShopDistrictCoordinate();

                        $coordinateModel->operation_shop_district_id = $insert_id;
                        $coordinateModel->operation_shop_district_name = $v['operation_shop_district_name'];
                        $coordinateModel->operation_city_id = $city_id;
                        $coordinateModel->operation_city_name = $city_name;
                        $coordinateModel->operation_area_id = $values['operation_area_id'];
                        $coordinateModel->operation_area_name = $values['operation_area_name'];
                        $coordinateModel->operation_shop_district_coordinate_start_longitude = $v['l_n_t'][$i]['operation_shop_district_coordinate_start_longitude'];
                        $coordinateModel->operation_shop_district_coordinate_start_latitude = $v['l_n_t'][$i]['operation_shop_district_coordinate_start_latitude'];
                        $coordinateModel->operation_shop_district_coordinate_end_longitude = $v['l_n_t'][$i]['operation_shop_district_coordinate_end_longitude'];
                        $coordinateModel->operation_shop_district_coordinate_end_latitude = $v['l_n_t'][$i]['operation_shop_district_coordinate_end_latitude'];

                        $coordinateModel->insert();
                    }
                }
            }
        }
    }

    public static function getCityShopDistrictListByNameAndCityId($city_id,$name){
        $condition = '1=1';
        if($city_id){
            $condition .= ' and operation_city_id='.$city_id;
        }
        if($name){
            $condition .=" and operation_shop_district_name like'%$name%'";
        }
        return self::find()->asArray()->where($condition)->all();

    }
}
