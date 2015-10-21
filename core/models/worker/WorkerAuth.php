<?php

namespace core\models\worker;

use Yii;

/**
 * This is the model class for table "ejj_worker_auth".
 *
 * @property integer $worker_id
 * @property integer $worker_auth_status
 * @property string $worker_auth_failed_reason
 * @property integer $worker_basic_training_status
 * @property integer $worker_ontrial_status
 * @property string $worker_ontrial_failed_reason
 * @property integer $worker_onboard_status
 * @property string $worker_onboard_failed_reason
 * @property integer $worker_rising_training_status
 */
class WorkerAuth extends \common\models\WorkerAuth
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ejj_worker_auth';
    }
}
