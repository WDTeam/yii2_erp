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

    /*
     * 关联Worker表
     */
    public function getWorker(){
        $condition = ['worker_is_block'=>0,'worker_is_blacklist'=>0,'worker_is_vacation'=>0];
        return $this->hasMany(Worker::className(),['id'=>'worker_id'])->onCondition($condition)->select('id,shop_id,worker_name,worker_phone,worker_idcard,worker_rule_id,worker_type')->innerJoinWith('shop');
    }

    /*
     *
     */
    public function getShop(){
        return $this->hasOne(Shop::className(),['id'=>'shop_id']);
    }
    /*
     * 关联District表
     */
    public function getDistrict(){
        return $this->hasMany(CoreOperationShopDistrict::className(),['id'=>'operation_shop_district_id']);
    }

}
