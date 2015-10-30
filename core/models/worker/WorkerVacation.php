<?php

namespace core\models\worker;

use Yii;

/**
 * This is the model class for table "{{%worker_vacation}}".
 *
 * @property integer $id
 * @property integer $worker_id
 * @property integer $worker_vacation_start_time
 * @property integer $worker_vacation_finish_time
 * @property integer $worker_vacation_type
 * @property string $worker_vacation_extend
 * @property integer $created_ad
 * @property integer $updated_ad
 * @property integer $admin_id
 */
class WorkerVacation extends \dbbase\models\worker\WorkerVacation
{
    /**
     * @param $vacationType
     * @return string
     */
    public static function getWorkerVacationTypeShow($vacationType){
        if($vacationType=1){
            return '休假';
        }else{
            return '事假';
        }
    }
}
