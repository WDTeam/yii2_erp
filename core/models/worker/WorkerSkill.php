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
class WorkerSkill extends \dbbase\models\worker\WorkerSkill
{
    /**
     * 获取阿姨所有技能
     * @param $worker_id
     * @return array
     */
    public static function getWorkerSkill($worker_id){
        $workerSkillConfig = WorkerSkillConfig::getWorkerSkillConfig();
        $workerSkillResult = self::find()->select('worker_skill_id')->where(['worker_id'=>$worker_id])->asArray()->all();
        $workerSkill = [];
        foreach ((array)$workerSkillResult as $key=>$val) {
            if(isset($workerSkillConfig[$val['worker_skill_id']])){
                $workerSkill[] = [
                    'worker_skill_id'=>$val['worker_skill_id'],
                    'worker_skill_name'=>$workerSkillConfig[$val['worker_skill_id']]
                ];
            }
        }
        return $workerSkill;
    }
}
