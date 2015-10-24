<?php

namespace boss\models\worker;

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
class WorkerBlock extends \core\models\worker\WorkerBlock
{
    public function getDateRange(){
        if(empty($this->worker_block_start_time) || empty($this->worker_block_finish_time)){
            $dateRange = '';
        }else{
            $dateRange = date('Y-m-d',$this->worker_block_start_time).'至'.date('Y-m-d',$this->worker_block_finish_time);
        }
        return $dateRange;
    }
}
