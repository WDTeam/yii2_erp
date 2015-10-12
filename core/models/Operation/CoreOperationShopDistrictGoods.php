<?php

namespace core\models\Operation;

use Yii;
use common\models\Operation\CommonOperationShopDistrictGoods;

class CoreOperationShopDistrictGoods extends CommonOperationShopDistrictGoods
{
    public static $city_id;
    public static $goods_id;
    /**
     * 插入城市商圈商品
     */
    public static function handleReleaseCity($cityinfo, $shopdistrictinfo, $goodinfo, $settinghopdistrictgoods){
        $cityid = $cityinfo[0];  //商圈id
        $cityname = $cityinfo[1]; //商圈名称
        $shop_district_goods_data = array();
        $fields = [
            'operation_shop_district_goods_name',
            'operation_shop_district_goods_no',
            'operation_goods_id',
            'operation_shop_district_id',
            'operation_shop_district_name',
            'operation_city_id',
            'operation_city_name',
            'operation_category_id',
            'operation_category_ids',
            'operation_category_name',
            'operation_shop_district_goods_introduction',
            'operation_shop_district_goods_english_name',
            'operation_shop_district_goods_start_time',
            'operation_shop_district_goods_end_time',
            'operation_shop_district_goods_service_interval_time',
            'operation_shop_district_goods_service_estimate_time',
            'operation_spec_info',
            'operation_spec_strategy_unit',
            'operation_shop_district_goods_price',
            'operation_shop_district_goods_balance_price',
            'operation_shop_district_goods_additional_cost',
            'operation_shop_district_goods_lowest_consume',
            'operation_shop_district_goods_lowest_consume_num',
            'operation_shop_district_goods_price_description',
            'operation_shop_district_goods_market_price',
            'operation_tags',
            'operation_goods_img',
            'operation_shop_district_goods_status',
            'created_at',
            'updated_at',
        ];
        $i = 0;
        foreach((array)$goodinfo['goodids'] as $key => $value){
            $goodsid = $value;
//            self::delCityShopDistrictGoods($goodsid, $cityid);
            self::setCityShopDistrictGoodsStatus($goodsid, $cityid);
            foreach((array)$shopdistrictinfo as $k => $v){
                $shop_district = explode('-', $v);
                $shop_district_goods_data[$i][] = $goodinfo['goodscontent'][$goodsid]['operation_goods_name'];  //商品名称
                $shop_district_goods_data[$i][] = $goodinfo['goodscontent'][$goodsid]['operation_goods_no'];  //商品货号
                $shop_district_goods_data[$i][] = $goodsid;  //商品id
                $shop_district_goods_data[$i][] = $shop_district[0];  //商圈id
                $shop_district_goods_data[$i][] = $shop_district[1];  //商圈名称
                $shop_district_goods_data[$i][] = $cityid;  //城市id
                $shop_district_goods_data[$i][] = $cityname;  //城市名称
                $shop_district_goods_data[$i][] = $goodinfo['goodscontent'][$goodsid]['operation_category_id'];  //对应服务品类编号（所属分类编号冗余）
                $shop_district_goods_data[$i][] = $goodinfo['goodscontent'][$goodsid]['operation_category_ids'];  //对应服务品类的所有编号以“,”关联
                $shop_district_goods_data[$i][] = $goodinfo['goodscontent'][$goodsid]['operation_category_name'];  //对应服务品类名称（所属分类名称冗余）
                $shop_district_goods_data[$i][] = $goodinfo['goodscontent'][$goodsid]['operation_goods_introduction'];  //服务类型简介
                $shop_district_goods_data[$i][] = $goodinfo['goodscontent'][$goodsid]['operation_goods_english_name'];  //商品英文名称
                
                $shop_district_goods_data[$i][] = $settinghopdistrictgoods['operation_goods_start_time'][$shop_district[0]];  //服务开始时间
                $shop_district_goods_data[$i][] = $settinghopdistrictgoods['operation_goods_end_time'][$shop_district[0]];  //服务结束时间
                
                $shop_district_goods_data[$i][] = $goodinfo['goodscontent'][$goodsid]['operation_goods_service_interval_time'];  //服务间隔时间(单位：分钟)
                $shop_district_goods_data[$i][] = $goodinfo['goodscontent'][$goodsid]['operation_goods_service_estimate_time'];  //预计服务时长(单位：分钟)
                $shop_district_goods_data[$i][] = $goodinfo['goodscontent'][$goodsid]['operation_spec_info'];  //规格id
                $shop_district_goods_data[$i][] = $goodinfo['goodscontent'][$goodsid]['operation_spec_strategy_unit'];  //计量单位
                
                $shop_district_goods_data[$i][] = $settinghopdistrictgoods['operation_goods_price'][$shop_district[0]];  //售价
                
                $shop_district_goods_data[$i][] = $goodinfo['goodscontent'][$goodsid]['operation_goods_balance_price'];   //阿姨结算价格
                $shop_district_goods_data[$i][] = $goodinfo['goodscontent'][$goodsid]['operation_goods_additional_cost'];  //附加费用
                
                $shop_district_goods_data[$i][] = $settinghopdistrictgoods['operation_goods_price'][$shop_district[0]]*$settinghopdistrictgoods['operation_goods_lowest_consume'][$shop_district[0]];  //最低消费价格
                $shop_district_goods_data[$i][] = $settinghopdistrictgoods['operation_goods_lowest_consume'][$shop_district[0]];  //最低消费数量
                
                $shop_district_goods_data[$i][] = $goodinfo['goodscontent'][$goodsid]['operation_goods_price_description'];  //价格备注
                
                $shop_district_goods_data[$i][] = $settinghopdistrictgoods['operation_goods_market_price'][$shop_district[0]];  //市场价格
                
                $shop_district_goods_data[$i][] = $goodinfo['goodscontent'][$goodsid]['operation_tags'];  //个性标签
                $shop_district_goods_data[$i][] = $goodinfo['goodscontent'][$goodsid]['operation_goods_img'];  //商品图片
                $shop_district_goods_data[$i][] = 1; //商品状态（1:上架 2:下架）
                $shop_district_goods_data[$i][] = time(); //创建时间
                $shop_district_goods_data[$i][] = time(); //更新时间
                $i++;
            }
        }
        Yii::$app->db->createCommand()->batchInsert(self::tableName(), $fields, $shop_district_goods_data)->execute();
    }
    
    public static function getShopDistrictGoodsInfo($city_id = '', $shop_district = '', $goods_id = ''){
        if(empty($city_id) || empty($shop_district) || empty($goods_id)){
            return '';
        }else{
            return self::find()->select(['operation_goods_id', 'operation_shop_district_goods_name', 'operation_shop_district_goods_introduction', 'operation_shop_district_goods_price', 'operation_shop_district_goods_lowest_consume_num', 'operation_shop_district_goods_lowest_consume', 'operation_shop_district_goods_market_price', 'created_at'])->asArray()->where(['operation_city_id' => $city_id, 'operation_shop_district_id' => $shop_district, 'operation_goods_id' => $goods_id])->One();
        }
    }
    
    public static function getShopDistrictGoodsList($city_id = '', $shop_district = ''){
        if(empty($city_id) || empty($shop_district)){
            return '';
        }else{
            return self::find()->select(['operation_goods_id', 'operation_shop_district_goods_name', 'operation_shop_district_goods_introduction', 'operation_shop_district_goods_price', 'operation_shop_district_goods_lowest_consume_num', 'operation_shop_district_goods_lowest_consume', 'operation_shop_district_goods_market_price', 'created_at'])->asArray()->where(['operation_city_id' => $city_id, 'operation_shop_district_id' => $shop_district])->All();
        }
    }
    
    /**
     * 查询城市下面的所有商品
     * @param type $city_id
     * @return string
     */
    public static function getCityShopDistrictGoodsList($city_id = ''){
        self::$city_id = $city_id;
        if(empty($city_id)){
            return '';
        }else{
            return self::find()->where(['operation_city_id' => $city_id])->groupBy('operation_goods_id');
        }
    }
    
    /*
     * 城市下边商品的状态置为下架
     */
    public static function setCityShopDistrictGoodsStatus($goodsid, $cityid){
        Yii::$app->db->createCommand()->update(self::tableName(), ['operation_shop_district_goods_status' => 2], ['operation_goods_id' => $goodsid, 'operation_city_id' => $cityid])->execute();
    }


    /**
     * 删除城市下边商品
     * @param type $goods_id
     * @param type $city_id
     */
    public static function delCityShopDistrictGoods($goods_id, $city_id){
        return self::deleteAll(['operation_goods_id' => $goods_id, 'operation_city_id' => $city_id]);
    }
    
    /**
     * 查找商品是否存在
     */
    public static function getCityShopDistrictGoodsInfo($city_id, $goods_id){
        return self::find()->asArray()->where(['operation_city_id' => $city_id, 'operation_goods_id' => $goods_id])->All();
    }
}
