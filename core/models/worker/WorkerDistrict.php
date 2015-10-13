<?php

namespace core\models\worker;

use common\models\Shop;
use Yii;
use core\models\worker\Worker;
use core\models\Operation\CoreOperationShopDistrict;
/**
 * This is the model class for table "{{%worker_district}}".
 *
 * @property integer $id
 * @property integer $worker_id
 * @property integer $operation_shop_district_id
 * @property integer $created_ad
 */
class WorkerDistrict extends \common\models\WorkerDistrict
{

    public static function saveWorkerDistrict($worker_id,$workerDistrictArr){
        $model = self::find()->where;
        if($workerDistrictArr){

        }

    }

    /*
     * 关联District表
     */
    public function getDistrict(){
        return $this->hasMany(CoreOperationShopDistrict::className(),['id'=>'operation_shop_district_id']);
    }


}
