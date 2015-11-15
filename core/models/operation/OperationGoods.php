<?php

namespace core\models\operation;

use Yii;
use boss\models\operation\OperationShopDistrictGoods;
use yii\web\UploadedFile;


/**
 * This is the model class for table "{{%operation_goods}}".
 *
 * @property integer $id
 * @property string $operation_goods_name
 * @property integer $operation_category_id
 * @property string $operation_category_ids
 * @property string $operation_category_name
 * @property string $operation_goods_introduction
 * @property string $operation_goods_english_name
 * @property string $operation_goods_start_time
 * @property string $operation_goods_end_time
 * @property string $operation_goods_service_time_slot
 * @property integer $operation_goods_service_interval_time
 * @property integer $operation_price_strategy_id
 * @property string $operation_price_strategy_name
 * @property string $operation_goods_price
 * @property string $operation_goods_balance_price
 * @property string $operation_goods_additional_cost
 * @property string $operation_goods_lowest_consume
 * @property string $operation_goods_price_description
 * @property string $operation_goods_market_price
 * @property string $operation_tags
 * @property string $operation_goods_app_ico
 * @property string $operation_goods_type_pc_ico
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationGoods extends \dbbase\models\operation\OperationGoods
{
    public static function getCategoryGoods($categoryid){
        return self::find()->asArray()->where(['operation_category_id' => $categoryid])->All();
    }
    
    public static function getCategoryGoodsInfo($categoryid, $city_id = ''){
        $data = self::getCategoryGoods($categoryid);
        $d = array();
        foreach((array)$data as $key => $value){
            //查找该商品是否在该城市存在，如不存在则返回
//            $goodsstatus = OperationShopDistrictGoods::getCityShopDistrictGoodsInfo($city_id, $value['id']);
//            if(empty($goodsstatus)){
                $d[$value['id'].'-'.$value['operation_goods_name']] = $value['operation_goods_name'];
//            }
        }
        return $d;
    }
    
    /**
     * 获取多个商品详细
     * @param type $goods_id
     */
    public static function getGoodsList($goods_ids = null){
        $data = array();
        if(!empty($goods_ids)){
            foreach((array)$goods_ids as $key => $value){
               $data[$key] = self::getGoodsInfo($value);
            }
        }
        return $data;
    }
    
    /**
     * 获取单个商品详细
     * @param type $goods_id
     */
    public static function getGoodsInfo($goods_id){
        return self::find()->asArray()->where(['id' => $goods_id])->One();
    }

    /**
    * 生成商品id和name
    * @date: 2015-11-15
    * @author: peak pan
    * @return:
    **/
    
    public static function getAllCategory_goods()
    {
    	$date=self::find()->all();
    	$goodname=\yii\helpers\ArrayHelper::map($date, 'id', 'operation_goods_name');
    	return $goodname;
    
    }
    
    /**
     * 上传图片到七牛服务器
     * @param string $field 上传文件字段名
     * @return string $imgUrl 文件URL
     */
    public function uploadImgToQiniu($field){
        $fileinfo = UploadedFile::getInstance($this, $field);
        if(!empty($fileinfo)){
            $key = time().mt_rand('1000', '9999').uniqid();
            \Yii::$app->imageHelper->uploadFile($fileinfo->tempName, $key);
            $imgUrl = \Yii::$app->imageHelper->getLink($key);
            $this->$field = $imgUrl;
        }
    }

    /**
     * 更新冗余的规格名称
     *
     * @param integer $operation_spec_info            规格编号
     * @param string  $operation_spec_strategy_unit   规格单位备注
     */
    public static function updateGoodsSpec($operation_spec_info, $operation_spec_strategy_unit)
    {
        self::updateAll(['operation_spec_strategy_unit' => $operation_spec_strategy_unit], 'operation_spec_info = ' . $operation_spec_info);
    }

    /**
     * 更新冗余的服务品类名称
     *
     * @param integer   $operation_category_id      服务品类编号
     * @param string    $operation_category_name    服务品类名称
     */
    public static function updateCategoryName($operation_category_id, $operation_category_name)
    {
        self::updateAll(['operation_category_name' => $operation_category_name], 'operation_category_id= ' . $operation_category_id);
    }
}
