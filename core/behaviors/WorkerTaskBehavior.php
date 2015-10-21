<?php
namespace core\behaviors;

use yii\base\Behavior;
use core\models\worker\WorkerTask;
class WorkerTaskBehavior extends Behavior
{
    public function events(){
        return [
           
        ];
    }
    /**
     * 自动处理阿姨任务
     * 实现思路：
     * 1、自动生成阿姨任务记录，有则取，无则建
     * 2、循环任务，查询获取在符合任务时间段内各项条件的数值
     * 3、保存数值，运算是否达到条件完成。
     */
    public function autoRunWorkerTask($worker_id)
    {
        $tasks = WorkerTask::autoCreateTaskLog($worker_id);
        foreach ($tasks as $task){
            $conVals = $this->getConditionsValues($task->worker_task_start, $task->worker_task_end, $worker_id);
            $task->setValues($conVals);
            $task->calculateIsDone();
        }
    }
    /**
     * 指定时间段内阿姨各个条件完成值
     *  条件类型：
     *  1=>'取消订单 ',
        2=>'拒绝订单',
        3=>'服务老用户',
        4=>'主动接单',
        5=>'完成工时',
        6=>'完成小保养 ',个数
     * @param unknown $start_time
     * @param unknown $end_time
     * @param unknown $worker_id
     */
    public function getConditionsValues($start_time, $end_time, $worker_id)
    {
        return [
            1=>3,
            2=>5,
            3=>8,
            4=>1,
            5=>22,
            6=>3,
        ];
    }
}