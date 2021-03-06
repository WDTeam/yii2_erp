<?php

namespace core\models\worker;

use Yii;
use yii\helpers\ArrayHelper;

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
class WorkerSkillConfig extends \dbbase\models\worker\WorkerSkillConfig
{
    /**
     * 获取阿姨技能配置信息
     * @return array
     */
    public static function getWorkerSkillConfig(){
        $result = self::findAll(['isdel'=>0]);
        return ArrayHelper::map($result,'id','worker_skill_name');
    }
}
