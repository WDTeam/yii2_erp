<?php
namespace console\models;

class WorkerTask {
    static public function createWorker(){
        
        //创建Timer1:用来检查新订单，创建任务 
        //创建Timer2:用来检查5分钟没有人接的订单，创建任务 
        //创建Timer3:用来检查35分钟没有人接的订单，创建任务 
        //创建Timer4:用来检查65分钟没有人接的订单，创建任务 
        
        //创建任务1：发送消息给全职阿姨
        //创建任务2：发送消息给兼职阿姨
        //创建任务3：发送消息给小家政
        //创建任务4：订单进入认同指派列表
        
        
        
//        $url = 'http://[domain]/create-worker';
    }
    
    static public function create(){
        
    }
    
    static public function createTask(){
        $url = 'http://[domain]/create-worker';
        return $taskid;
    }
}
