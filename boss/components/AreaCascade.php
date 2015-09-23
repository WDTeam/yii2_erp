<?php
namespace boss\components;

use Yii;
use boss\models\Operation\OperationArea;
use yii\helpers\Html;

/**
 * 
 */
class AreaCascade extends Html{
    /**
     * 
     * @param type $name
     * @param type $downList
     * @return type 
     */
    public static function cascadeAll($name, $options = []){
        return  static::areaProvince($name, $options)
                .static::areaCity($name, $options)
                .static::areaCounty($name, $options)
                .static::areaTown($name, $options);
    }
    
    
    public static function areaTown($name, $options = [], $county_id = null, $selection = '选择乡镇（街道）'){
        $name = $name.'[town]';
        $options['id'] = 'town';
        $items = [$selection];
        if(!empty($county_id)){
            $towns = OperationArea::getProvinces($county_id);
            foreach($towns as $key => $town){
                $items[$town->id] = $town->area_name;
            }
        }
        return static::dropDownList($name, '', $items, $options);
    }
    
    public static function areaCounty($name, $options = [], $city_id = null, $selection = '选择县（区）'){
        $name = $name.'[county]';
        $options['id'] = 'county';
        $items = [$selection];
        if(!empty($city_id)){
            $counties = OperationArea::getProvinces($city_id);
            foreach($counties as $key => $county){
                $items[$county->id] = $county->area_name;
            }
        }
        return static::dropDownList($name, '', $items, $options);
    }
    
    public static function areaCity($name, $options = [], $province_id = null, $selection = '选择城市'){
        $name = $name.'[city]';
        $options['id'] = 'city';
        $items = [$selection];
        if(!empty($province_id)){
            $citys = OperationArea::getCity($province_id);
            foreach($citys as $key => $city){
                $items[$city->id] = $city->area_name;
            }
        }
        return static::dropDownList($name, '', $items, $options);
    }

    private static function areaProvince($name, $options = [],$selection = '选择省份'){
        $name = $name.'[province]';
        $provinces = OperationArea::getProvinces();
        $items = [$selection];
        $options['id'] = 'province';
        foreach($provinces as $key => $prov){
            $items[$prov->id] = $prov->area_name;
        }
        return static::dropDownList($name, '', $items, $options);
    }


//    public static function
}
