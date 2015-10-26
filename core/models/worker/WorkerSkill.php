<?php

namespace core\models\worker;

use Yii;
use boss\models\worker\WorkerSkillConfig;
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
        $workerSkillConfig = WorkerSkillConfig::getWorkerSkillConfig();
        var_dump($workerSkillConfig);die;
        $workerSkill = self::find()->where(['worker_id'=>$worker_id])->asArray()->all();
        foreach ((array)$workerSkill as $key=>$val) {
            $workerSkill[$key]['worker_skill_name'] = 1;
        }
        return $workerSkill;
    }
}
