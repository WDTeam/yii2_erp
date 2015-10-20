<?php
namespace core\behaviors;

use yii\base\Behavior;
use core\models\order\Order;
use core\models\worker\WorkerTaskLog;
class WorkerTaskBehavior extends Behavior
{
    public function events(){
        return [
//             Order::EVENT_CREATE_BY_USER=>'',
            Order::EVENT_ACCEPT_BY_WORKER=>'acceptByWorker',
            Order::EVENT_CANCEL_BY_WORKER=>'',
            Order::EVENT_DONE_BY_WORKER=>'',
            Order::EVENT_REJECT_BY_WORKER=>'',
        ];
    }
    /**
     * 阿姨主动接单
     * 实现规则：
     * 1、先获取阿姨当前时间可参加的任务列表
     * 2、根据任务和时间生成对应阿姨的任务记录，前提是此记录不存在
     * 3、查询此时间段内阿姨主动接单数，并更新主动接单值
     * @param Object $event
     */
    public function acceptByWorker($event)
    {
        $order = $event->sender;
        $ext_worker = $order->orderExtWorker;
        
        if(!empty($ext_worker) && $ext_worker->order_worker_assign_type==1){
            $log = new WorkerTaskLog();
            $log->worker_id = $ext_worker->worker_id;
        }
        
        
    }
}