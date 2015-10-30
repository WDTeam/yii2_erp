<?php

namespace core\models\worker;

use Yii;

/**
 * This is the model class for table "{{%worker_device}}".
 *
 * @property integer $worker_id
 * @property double $worker_device_curr_lng
 * @property double $worker_device_curr_lat
 * @property string $worker_device_client_version
 * @property string $worker_device_version_name
 * @property string $worker_device_token
 * @property string $worker_device_mac_addr
 * @property string $worker_device_login_ip
 * @property integer $worker_device_login_time
 * @property integer $created_ad
 * @property integer $updated_ad
 */
class WorkerDevice extends \dbbase\models\worker\WorkerDevice
{

}
