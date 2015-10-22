<?php

namespace core\models\worker;

use Yii;

/**
 * This is the model class for table "{{%worker_schedule}}".
 *
 * @property integer $id
 * @property integer $worker_id
 * @property integer $worker_schedule_start_date
 * @property integer $worker_schedule_end_date
 * @property string $worker_schedule_timeline
 * @property integer $created_ad
 */
class WorkerSchedule extends \common\models\WorkerSchedule
{
    public static function getWeekdayShow($weekday){
        switch($weekday){
            case 1:
                return '周一';
            case 2:
                return '周二';
            case 3:
                return '周三';
            case 4:
                return '周四';
            case 5:
                return '周五';
            case 6:
                return '周六';
            case 7:
                return '周日';
        }
    }
}
