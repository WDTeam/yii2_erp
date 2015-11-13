<?php

namespace core\models\worker;

use dbbase\models\Shop;
use Yii;
use core\models\worker\Worker;
use core\models\operation\OperationShopDistrict;
/**
 * This is the model class for table "{{%worker_district}}".
 *
 * @property integer $id
 * @property integer $worker_id
 * @property integer $operation_shop_district_id
 * @property integer $created_ad
 */
class WorkerDistrict extends \dbbase\models\worker\WorkerDistrict
{

    //public static function updateWorkerDistrict();

    /*
     * 关联District表
     */
    public function getDistrict(){
        return $this->hasMany(OperationShopDistrict::className(),['id'=>'operation_shop_district_id']);
    }

    public static function deleteDistrict($district_id){
        if(empty($district_id)){
            return false;
        }
        self::deleteAll(['operation_shop_district_id'=>$district_id]);
        WorkerForRedis::deleteDistrictToRedis($district_id);
    }

}
