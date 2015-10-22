<?php

namespace core\models\worker;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

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
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
        ];
    }
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
     * 调用WorkerTask类里的方法判断完成
     */
    public function calculateIsDone()
    {
        if($this->worker_task_is_done==1){
            return true;
        }
        $task = WorkerTask::findOne(['id'=>$this->worker_task_id]);
        $is_done = $task->calculateValuesIsDone($this->getConditionsValues());
        if($is_done){
            $this->worker_task_is_done = 1;
            $this->worker_task_done_time = time();
            return $this->save();
        }
        return $is_done;
    }
    /**
     * 存数值
     * @param array $metas. eg: ['1'=>5,2=>6]
     */
    public function setValues($metas)
    {
        foreach ($metas as $condition=>$value){
            $_meta = WorkerTaskLogmeta::find()->where([
                'worker_task_id'=>$this->worker_task_id,
                'worker_tasklog_id'=>$this->id,
                'worker_id'=>$this->worker_id,
                'worker_tasklog_condition'=>$condition,
            ])->one();
            if(empty($_meta)){
                $_meta = new WorkerTaskLogmeta();
                $_meta->setAttributes([
                    'worker_task_id'=>$this->worker_task_id,
                    'worker_tasklog_id'=>$this->id,
                    'worker_id'=>$this->worker_id,
                    'worker_tasklog_condition'=>$condition,
                ]);
            }else{
                $_meta->worker_tasklog_value = $value;
                $_meta->save();
            }
        }
        return true;
    }
    /**
     * 当前阿姨任务列表
     */
    public static function getCurListByWorkerId($worker_id)
    {
        $time = time();
        return self::find()->where([
            'worker_id'=>$worker_id
        ])->filterWhere(['<', 'worker_task_log_start', $time])
        ->filterWhere(['>=', 'worker_task_log_end', $time])
        ->all();
    }
}
