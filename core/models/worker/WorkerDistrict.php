<?php

namespace core\models\worker;

use dbbase\models\Shop;
use Yii;
use core\models\worker\Worker;
use core\models\operation\OperationShopDistrict;
use yii\helpers\ArrayHelper;

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

    public static function getDistrictWorkerIds($district_id){
        if(empty($district_id)){
            return [];
        }
        $result = self::find()->where(['operation_shop_district_id'=>$district_id])->asArray()->all();
        if($result){
            return ArrayHelper::getColumn($result,'worker_id');
        }else{
            return [];
        }
    }

    /**
     * 清除指定商圈中所有阿姨
     * 商圈下线或删除时调用
     * @param $district_id
     * @return bool
     */
    public static function deleteDistrictWorker($district_id){
        if(empty($district_id)){
            return false;
        }
        self::deleteAll(['operation_shop_district_id'=>$district_id]);
        WorkerForRedis::deleteDistrictToRedis($district_id);
    }

}
