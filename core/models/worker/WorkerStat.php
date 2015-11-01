<?php

namespace core\models\worker;

use Yii;

/**
 * This is the model class for table "{{%worker_stat}}".
 *
 * @property integer $worker_id
 * @property integer $worker_stat_order_num
 * @property string $worker_stat_order_money
 * @property integer $worker_stat_order_refuse
 * @property integer $worker_stat_order_complaint
 * @property integer $worker_stat_sale_cards
 * @property integer $updated_ad
 */
class WorkerStat extends \dbbase\models\worker\WorkerStat
{
    /**
     * 更新阿姨接单数量
     * @param int $worker_id
     * @param int $addNum 添加数量
     * @return bool
     */
    public static function updateWorkerStatOrderNum($worker_id,$addNum=1){
        if(empty($worker_id) || empty($addNum)){
            return false;
        }
        $model = self::findOne($worker_id);
        $model->worker_stat_order_num = intval($model->worker_stat_order_num)+intval($addNum);
        return $model->save();
    }

    /**
     * 更新阿姨拒单数量
     * @param int $worker_id
     * @param int $addNum 添加数量
     * @return bool
     */
    public static function updateWorkerStatRefuseNum($worker_id,$addNum=1){
        if(empty($worker_id) || empty($addNum)){
            return false;
        }
        $model = self::findOne($worker_id);
        $model->worker_stat_order_refuse = intval($model->worker_stat_order_refuse)+intval($addNum);
        return $model->save();
    }
}
