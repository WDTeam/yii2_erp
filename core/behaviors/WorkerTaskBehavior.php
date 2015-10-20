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
    
    public function acceptByWorker($event)
    {
//         var_dump($event);exit;
//         $log = new WorkerTaskLog();
    }
}