<?php

namespace core\models\worker;

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

    /*
     * 关联Worker表
     */
    public function getWorker(){
        $condition = ['worker_is_block'=>0,'worker_is_blacklist'=>0,'worker_is_vacation'=>0];
        return $this->hasMany(Worker::className(),['id'=>'worker_id'])->onCondition($condition);
    }
    /*
     * 关联District表
     */
    public function getDistrict(){
        return $this->hasMany(CoreOperationShopDistrict::className(),['id'=>'operation_shop_district_id']);
    }

}
