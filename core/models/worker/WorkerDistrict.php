<?php

namespace core\models\worker;

use Yii;
use core\models\worker\Worker;

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


    public function getworker(){
        $condition = ['worker_is_block'=>0,'worker_is_blacklist'=>0,'worker_is_vacation'=>0];
        return $this->hasMany(Worker::className(),['id'=>'worker_id'])->onCondition($condition);
    }

}
