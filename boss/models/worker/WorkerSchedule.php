<?php

namespace boss\models\worker;

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
class WorkerSchedule extends \core\models\worker\WorkerSchedule
{
}
