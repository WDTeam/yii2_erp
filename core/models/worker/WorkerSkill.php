<?php

namespace core\models\worker;

use Yii;

/**
 * This is the model class for table "{{%worker_skill}}".
 *
 * @property integer $id
 * @property integer $worker_id
 * @property integer $worker_skill_id
 * @property integer $created_ad
 */
class WorkerSkill extends \common\models\worker\WorkerSkill
{
    public static function getWorkerSkill($worker_id){
        $workerSkill = self::find()->where(['worker_id'=>$worker_id])->asArray()->all();
        foreach ((array)$workerSkill as $key=>$val) {
            $workerSkill[$key];
        }

    }
}
