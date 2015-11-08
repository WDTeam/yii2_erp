<?php

namespace core\models\operation;

use Yii;
use dbbase\models\operation\OperationCity as CommonOperationCity;

/**
 * This is the model class for table "{{%operation_city}}".
 *
 * @property integer $id
 * @property string $operation_city_name
 * @property integer $operation_city_is_online
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationCity extends CommonOperationCity
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
     * 上线城市
     *
     * @param array $post    要上线的城市，服务类型，服务项目和商圈信息
     */
    public static function saveOnlineCity($post)
    {
        //城市数据
        $city_id = $post['city_id'];
        $city_name = $post['city_name'];

        unset($post['city_id']);
        unset($post['city_name']);
        unset($post['_csrf']);

        //去掉接收数据中没有选择的服务类型
        foreach ($post as $keys => $values) {
            if (!is_array($values)) {
                unset($post[$keys]);
            }
        }

        //服务类型的数据
        foreach ($post as $keys => $values) {

            //服务类型的id
            $operation_category_id = $keys;
            $operation_category_name = OperationCategory::getCategoryName($keys);

            //去掉接收数据中没有选中的或是没有输入销售价格的服务项目
            foreach ($values as $key => $value) {
                if (!isset($value['operation_goods_id']) || $value['operation_goods_id'] == '' || !isset($value['operation_goods_price']) || $value['operation_goods_price'] == '' || !isset($value['district']) || empty($value['district'])) {
                    unset($values[$key]);
                }
            }

            //如果没有输入销售价格,不能上线
            if (!isset($values) || empty($values)) {
                break;
            }

            //服务项目的数据
            foreach ($values as $key => $value) {
                $operation_goods_id = $value['operation_goods_id'];
                $operation_goods_name = $value['operation_goods_name'];
                $operation_goods_price = $value['operation_goods_price'];

                $operation_goods_market_price = $value['operation_goods_market_price'] ?  $value['operation_goods_market_price']: 0;

                $operation_shop_district_goods_lowest_consume_num = $value['operation_shop_district_goods_lowest_consume_num'] ? $value['operation_shop_district_goods_lowest_consume_num'] : 0;

                $operation_spec_strategy_unit = $value['operation_spec_strategy_unit'];

                //商圈的数据
                foreach ($value['district'] as $k => $v) {
                    $model = new OperationShopDistrictGoods();

                    //城市数据
                    $model->operation_city_id = $city_id;
                    $model->operation_city_name = $city_name;

                    //服务类型数据
                    $model->operation_category_id = $operation_category_id;
                    $model->operation_category_name = $operation_category_name;

                    //服务项目数据
                    $model->operation_goods_id = $operation_goods_id;
                    $model->operation_shop_district_goods_name = $operation_goods_name;
                    $model->operation_shop_district_goods_price = $operation_goods_price;
                    $model->operation_shop_district_goods_market_price = $operation_goods_market_price;
                    $model->operation_shop_district_goods_lowest_consume_num = $operation_shop_district_goods_lowest_consume_num;

                    //商圈数据
                    $model->operation_shop_district_id = $v;
                    $operation_shop_district_name = OperationShopDistrict::getShopDistrictName($v);
                    $model->operation_shop_district_name = $operation_shop_district_name;

                    //服务项目规格数据
                    $model->operation_spec_strategy_unit = $operation_spec_strategy_unit;

                    $model->insert();
                }
            }
        }
    }
}
