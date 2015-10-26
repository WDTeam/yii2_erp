<?php

namespace core\models\worker;

use Yii;

/**
 * This is the model class for table "{{%worker_skill_config}}".
 *
 * @property integer $id
 * @property integer $worker_skill_name
 * @property integer $created_ad
 * @property integer $updated_ad
 * @property integer $admin_id
 * @property double $isdel
 */
class WorkerSkillConfig extends \common\models\worker\WorkerSkillConfig
{
    public static function getWorkerSkillConfig(){
        return self::findAll(['isdel'=>0]);
    }
}
