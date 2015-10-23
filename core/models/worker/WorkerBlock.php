<?php

namespace core\models\worker;

use Yii;

/**
 * This is the model class for table "{{%worker_block}}".
 *
 * @property integer $id
 * @property integer $worker_id
 * @property integer $worker_block_start_time
 * @property integer $worker_block_finish_time
 * @property string $worker_block_reason
 * @property integer $worker_block_status
 * @property integer $created_ad
 * @property integer $updated_ad
 */
class WorkerBlock extends \common\models\WorkerBlock
{

}
