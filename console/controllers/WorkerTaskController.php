<?php
namespace console\controllers;

use yii\console\Controller;
use core\models\worker\WorkerTask;
use core\models\worker\Worker;
use yii\helpers\ArrayHelper;
use core\models\worker\WorkerTaskLog;
class WorkerTaskController extends Controller
{

    /**
     * 指定时间段内阿姨各个条件完成值
     *  条件类型：
         1=>'取消订单 ',
         2=>'拒绝订单',
         3=>'服务老用户',
         4=>'主动接单',
         5=>'完成工时',
         6=>'完成小保养 ',个数
     * @param unknown $start_time
     * @param unknown $end_time
     * @param unknown $worker_id
     */
    private function getConditionsValues($start_time, $end_time, $worker_id)
    {
        $data = [];
        //取消订单
        $sql = "SELECT COUNT(1) FROM {{%order_ext_worker}} AS a
        LEFT JOIN {{%order_history}} AS b
        ON a.`order_id`=b.order_id
        AND b.`order_status_dict_id`=16
        WHERE b.`order_status_dict_id`=16
        AND b.created_at>={$start_time}
        AND b.created_at<{$end_time}
        AND a.worker_id={$worker_id}";
        $data[1] = (int)\Yii::$app->db->createCommand($sql)->queryScalar();
        //拒绝订单
        $sql = "";
        //服务老用户,先算总数，
        $sql = "SELECT COUNT(1) as ct FROM {{%order_ext_worker}} AS a 
            LEFT JOIN {{%order_history}} AS b 
            ON a.`order_id`=b.order_id  AND b.`order_status_dict_id`=11 
            LEFT JOIN ejj_order_ext_customer AS c 
            ON c.`order_id`=a.`order_id`
            WHERE b.`order_status_dict_id`=11 
            AND b.created_at>={$start_time}
            AND b.created_at<{$end_time}
            AND a.`worker_id`={$worker_id} 
            GROUP BY c.`comment_id`";
        $args = (array)\Yii::$app->db->createCommand($sql)->queryColumn();
        $fdl = 0;
        foreach ($args as $d){
            if($d>1){
                $fdl++;
            }
        }
        $division = count($args)==0?1:count($args);
        $data[3] = (int)(($fdl/$division)*100);
        //主动接单
        $sql = "SELECT COUNT(1) FROM {{%order_ext_worker}} AS a
            LEFT JOIN {{%order_status_history}} AS b 
            ON a.`order_id`=b.`order_id` 
            AND b.order_status_dict_id=4
            WHERE a.order_worker_assign_type=1 
            AND a.worker_id={$worker_id} 
            AND b.order_status_dict_id=4
            AND b.`created_at`>={$start_time} 
            AND b.`created_at`<{$end_time}";
        $data[4] = (int)\Yii::$app->db->createCommand($sql)->queryScalar();
        //完成工时
        $sql = "SELECT COUNT(1) 
            FROM {{%order_ext_worker}} AS a 
            LEFT JOIN {{%order_history}} AS b ON a.`order_id`=b.order_id
            LEFT JOIN {{%order}} AS c ON c.`id`=a.`order_id`
            WHERE b.order_status_dict_id=11
            AND a.worker_id={$worker_id} 
            AND b.created_at>={$start_time}  
            AND b.created_at<{$end_time}";
        $data[5] = (int)\Yii::$app->db->createCommand($sql)->queryScalar();
        
        return $data;
    }
    /**
     * 定时处理已结束的任务周期
     * 定时生成阿姨任务
     * 实现思路：
     * 1、循环任务，查询获取昨日完成的任务
     * 2、计算时间段内各项条件的数值，保存数值，运算是否达到条件完成。
     * 3、自动生成阿姨任务记录，有则取，无则建.
     */
    public function actionIndex()
    {
        $tasks = (array)WorkerTaskLog::find()
        ->where('worker_task_is_done is NULL or worker_task_is_done=0')
        ->filterWhere(['<=','worker_task_log_end', strtotime("-1 day")])
        ->all();
        foreach ($tasks as $task){
            $conVals = $this->getConditionsValues($task->worker_task_log_start, $task->worker_task_log_end, $task->worker_id);
            $task->setValues($conVals);
            $task->calculateIsDone();
        }
        
        $models = Worker::find()->all();
        $total = count($models);
        echo "worker total: {$total}".PHP_EOL;
        foreach ($models as $model){
            $res =  (array)WorkerTask::autoCreateTaskLog($model->id);
            echo count($res).PHP_EOL;
        }
    }
}