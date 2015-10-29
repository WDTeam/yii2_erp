<?php

namespace boss\models\worker;

use Yii;

/**
 * This is the model class for table "{{%worker_vacation_application}}".
 *
 * @property integer $id
 * @property integer $worker_id
 * @property integer $worker_vacation_application_start_time
 * @property integer $worker_vacation_application_end_time
 * @property integer $worker_vacation_application_approve_status
 * @property integer $worker_vacation_application_approve_time
 * @property integer $created_ad
 */
class WorkerVacationApplication extends \core\models\worker\WorkerVacationApplication
{

    public static function CountApplication(){
        return self::find()->where(['worker_vacation_application_approve_status'=>0])->count();
    }
}
