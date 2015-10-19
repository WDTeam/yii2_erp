<?php

namespace core\models\worker;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%worker_task_log}}".
 *
 * @property integer $id
 * @property integer $worker_id
 * @property integer $worker_task_id
 * @property string $worker_task_name
 * @property integer $worker_task_start
 * @property integer $worker_task_end
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class WorkerTaskLog extends \common\models\WorkerTaskLog
{
    /**
     * 获取每个条件对应的所有数值
     */
    public function getConditionsValues()
    {
        $models = (array)WorkerTaskLogmeta::find()
        ->select(['worker_tasklog_condition', 'worker_tasklog_value', 'worker_tasklog_is_done'])
        ->where(['worker_tasklog_id'=>$this->id])
        ->asArray()->all();
        return $models;
    }
    /**
     * 计算是否完成
     */
    public function calculateIsDone()
    {
        $task = WorkerTask::findOne(['id'=>$this->worker_task_id]);
        $is_done = $task->calculateValuesIsDone($this->getConditionsValues());
        $this->worker_task_is_done = $is_done;
        return $this->save();
    }
}
