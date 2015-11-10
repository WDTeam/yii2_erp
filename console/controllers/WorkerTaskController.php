<?php
/**
 * @author CoLee
 */
namespace console\controllers;

use yii\console\Controller;
use core\models\worker\WorkerTask;
use core\models\worker\Worker;
use yii\helpers\ArrayHelper;
use core\models\worker\WorkerTaskLog;
use core\models\worker\WorkerTaskCondition;
use core\components\ConsoleHelper;
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
//         * 1、循环任务，查询获取昨日之前结束的任务
//         * 2、计算时间段内各项条件的数值，保存数值，运算是否达到条件完成。
        $tasks = (array)WorkerTaskLog::find()
        ->select(['worker_task_is_done','worker_task_log_start', 'worker_task_log_end', 'worker_id'])
        ->where('worker_task_is_done is NULL or worker_task_is_done=0')
        ->andFilterWhere(['<=','worker_task_log_end', strtotime("-1 day")])
        ->all();
        ConsoleHelper::log('截至昨日已结束但未处理的任务（%s）个', [count($tasks)]);
        foreach ($tasks as $task){
            try{
                $conVals = $this->getConditionsValues($task->worker_task_log_start, $task->worker_task_log_end, $task->worker_id);
                $task->setValues($conVals);
                $is_done = $task->calculateIsDone();
                ConsoleHelper::log('阿姨（%s）的任务（%s）%s',[
                    $task->worker_id,
                    $task->worker_task_id,
                    ($is_done?'完成':'失败')
                ]);
            }catch(\Exception $e){
                echo 'has error';
            }
        }
//         * 3、自动生成阿姨任务记录，有则取，无则建.
        $models = Worker::find()->all();
        ConsoleHelper::log('阿姨总数（%s）',[count($models)]);
        foreach ($models as $model){
            try{
                $res =  (array)WorkerTask::autoCreateTaskLog($model->id);
                ConsoleHelper::log('阿姨（%s）分配到（%s）个任务',[$model->id,count($res)]);
            }catch(\Exception $e){
                echo 'has error';
            }
            
        }
        //检查已完成的任务，处理结算状态
        $tasks = (array)WorkerTaskLog::find()
        ->where('worker_task_is_done=1')
        ->andWhere('worker_task_is_settlemented is NULL OR worker_task_is_settlemented=0')
        ->all();
        ConsoleHelper::log('未结算总数（%s）',[count($tasks)]);
        foreach ($tasks as $task){
            try{
                $is_set = $task->setSettlemented();
                ConsoleHelper::log('阿姨任务（%s）%s结算',[
                    $task->worker_task_name,
                    ($is_set?'已':'未')
                ]);
            }catch(\Exception $e){
                echo 'has error';
            }
        }
    }
}