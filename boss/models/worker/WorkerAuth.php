<?php

namespace boss\models\worker;

use Yii;

/**
 * This is the model class for table "{{%worker_auth}}".
 *
 * @property integer $worker_id
 * @property integer $worker_auth_status
 * @property string $worker_auth_failed_reason
 * @property integer $worker_basic_training_status
 * @property integer $worker_ontrial_status
 * @property string $worker_ontrial_failed_reason
 * @property integer $worker_onboard_status
 * @property string $worker_onboard_failed_reason
 * @property integer $worker_upgrade_training_status
 */
class WorkerAuth extends \core\models\worker\WorkerAuth
{

}
