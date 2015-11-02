<?php
namespace console\controllers;

use yii\console\Controller;
use core\models\worker\WorkerTask;
use core\models\worker\Worker;
use yii\helpers\ArrayHelper;
use core\models\worker\WorkerTaskLog;
use core\models\worker\WorkerTaskCondition;
class WorkerTaskController extends Controller
{
    private function getConditionsValues($start_time, $end_time, $worker_id)
    {
        return WorkerTaskCondition::getConditionsValues($start_time, $end_time, $worker_id);
    }
    /**
     * 定时处理已结束的任务周期
     * 每天运行一次即可
     * 定时生成阿姨任务
     * 定时检查结算与否
     */
    public function actionIndex()
    {
//         * 1、循环任务，查询获取昨日完成的任务
//         * 2、计算时间段内各项条件的数值，保存数值，运算是否达到条件完成。
        $tasks = (array)WorkerTaskLog::find()
        ->select(['worker_task_is_done','worker_task_log_start', 'worker_task_log_end', 'worker_id'])
        ->where('worker_task_is_done is NULL or worker_task_is_done=0')
        ->andFilterWhere(['<=','worker_task_log_end', strtotime("-1 day")])
        ->all();
        foreach ($tasks as $task){
            $conVals = $this->getConditionsValues($task->worker_task_log_start, $task->worker_task_log_end, $task->worker_id);
            $task->setValues($conVals);
            $task->calculateIsDone();
        }
//         * 3、自动生成阿姨任务记录，有则取，无则建.
        $models = Worker::find()->all();
        $total = count($models);
        echo "worker total: {$total}".PHP_EOL;
        foreach ($models as $model){
            $res =  (array)WorkerTask::autoCreateTaskLog($model->id);
            echo count($res).PHP_EOL;
        }
        //检查结算
        $tasks = (array)WorkerTaskLog::find()
        ->where('worker_task_is_done=1')
        ->andWhere('worker_task_is_settlemented is NULL OR worker_task_is_settlemented=0')
        ->all();
        $total = count($tasks);
        echo "setSettlemented total: {$total}".PHP_EOL;
        foreach ($tasks as $task){
            $task->setSettlemented();
            echo 'task: '.$task->worker_task_name.' done.'.PHP_EOL;
        }
    }
}