<?php
/*
 * BOSS 自动派单运行服务实例
 * @author 张航<zhanghang@1jiajie.com>
 * @author 张旭刚<zhangxugang@corp.1jiajie.com>
 * @author 林洪优<linhongyou@1jiajie.com>
 * @link http://boss.1jiajie.com/auto-assign/
 * @copyright Copyright (c) 2015 E家洁 LLC
*/
define('DEBUG', 'on');
define("WEBPATH", str_replace("\\","/", __DIR__));
define("CONFIG_PATH", WEBPATH."/autoassign.config.php");
define("REDIS_IS_SERVER_SUSPEND","REDIS_IS_SERVER_SUSPEND");

$assign_config = require(CONFIG_PATH);

require_once(WEBPATH."/ClientCommand.php");
class server
{
    private $serv;
    private $ws;
    private $fd;
    private $data;
    private $redis;
    private $mongo = null;
    private $mongo_autoassign_log = null;
    private $isServerSuspend = false;
    private $isWorkerTaskRunning = false;
    private $config;
    private $timer_id;
 
    /**
     * [__construct description]
     * 构造方法中,初始化 $serv 服务
     */
    public function __construct($config) {
        echo date('Y-m-d H:i:s')." 自动指派服务启动中";
        $this->config = $config;
        $this->connectMongodb();//捕获了初始化异常，不影响主流程
        $this->recordMessageByMongodb('自动指派服务启动中');
        $this->recordMessageByMongodb('MONGODB_SERVER='.$this->config['MONGODB_SERVER'].';MONGODB_SERVER_DB_NAME='.$this->config['MONGODB_SERVER_DB_NAME'].';SWOOLE_SERVER_IP='.$this->config['SWOOLE_SERVER_IP'].';SERVER_LISTEN_PORT='.$this->config['SERVER_LISTEN_PORT']);
        $this->connectRedis();
        $this->redis->set(REDIS_IS_SERVER_SUSPEND,json_encode(false));
        $this->saveStatus(null);
        $this->serv = new swoole_websocket_server($config['SERVER_LISTEN_IP'], $config['SERVER_LISTEN_PORT']);
        //初始化swoole服务
        $this->serv->set(array(
            'worker_num'  => $config['WORKER_NUM'],
            'daemonize'   => $config['DAEMONIZE'],
            'max_request' => $config['MAX_REQUEST'],
            'log_file'    => $config['LOG_FILE'],
            'task_worker_num' => $config['TASK_WORKER_NUM'],
        ));

        //设置监听
        $this->serv->on('Start', array($this, 'onStart'));
        $this->serv->on('WorkerStart', array($this, 'onWorkerStart'));
        $this->serv->on('WorkerStop', array($this, 'onWorkerStop'));
        $this->serv->on('Connect', array($this, 'onConnect'));
        $this->serv->on("Close", array($this, 'onClose'));
        $this->serv->on("Task", array($this, 'onTask'));
        $this->serv->on("Finish", array($this, 'onFinish'));
        $this->serv->on("Message", array($this, 'onMessage'));
        $this->serv->on('Receive', array($this, 'onReceive'));
                
        echo "==>初始化完成";
        $this->recordMessageByMongodb('swoole初始化完成');
        //开启 
        $this->serv->start();
    }
    /*
     * Server 启动
     */
    public function onStart($server) {
        echo "==>已启动\n";
        $this->recordMessageByMongodb('onStart执行');
        echo date('Y-m-d H:i:s')." 主进程ID：= " .$this->config['SERVER_MASTER_PROCESS_ID']."\n";
        cli_set_process_title($this->config['SERVER_MASTER_PROCESS_ID']);
    }

    /*
     * WorkerStart 启动
     */
    function onWorkerStart(swoole_server $server, $worker_id) {
        //echo 'onWorkStart ID:=' . $worker_id . "\n";
        cli_set_process_title($this->config['SERVER_WORKER_PROCESS_ID'] . $worker_id);
        echo "worker_id ".$worker_id." is start";
        $this->recordMessageByMongodb("worker_id ".$worker_id." is start");
        // 只有当worker_id为0时才添加定时器,避免重复添加
        if ($worker_id == 0) {
            $workerProcessNum = $this->config['WORKER_NUM']+$this->config['TASK_WORKER_NUM'];
            echo date('Y-m-d H:i:s').' 工作进程ID:= '.$this->config['SERVER_WORKER_PROCESS_ID']." 已启动 ".$workerProcessNum." 进程\n";
            $this->config = require(CONFIG_PATH);
            $this->startTimer($server);
        }
    }
    /*
     * Worker Stop
     */
    function onWorkerStop($server, $worker_id) {
        echo date('Y-m-d H:i:s').' Worker Stop && Reload...'."\n";
    }

    /*
     * 获取本机IP
     */
    public function serverIP(){
        $serverIP = $this->config['SWOOLE_SERVER_IP'];
        return $serverIP;
    }
    /*
     * 连接 Redis
     */
    public function connectRedis(){
        $this->redis = new Redis();
        $this->redis->connect($this->config['REDIS_SERVER_IP'], $this->config['REDIS_SERVER_PORT']);
        $this->redis->auth($this->config['REDIS_SERVER_PASSWORD']);
        $this->recordMessageByMongodb('redis成功启动;'.'redis的ip和端口为:'.$this->config['REDIS_SERVER_IP'].':'.$this->config['REDIS_SERVER_PORT']);
    }
    
    /*
     * 连接Mongodb
     */
    public function connectMongodb(){
        try{
            $this->mongo = new MongoClient($this->config['MONGODB_SERVER'].'/'.$this->config['MONGODB_SERVER_DB_NAME']);
            if(isset($this->mongo)){
                $db = $this->mongo->selectDB($this->config['MONGODB_SERVER_DB_NAME']);
                if(isset($db)){
                    $this->mongo_autoassign_log = $db->selectCollection("autoassign_log");
                }
            }
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    
    /*
     * 通过mongodb记录日志，如果mongodb没有初始化成功，则不记录
     */
    public function recordMessageByMongodb($message){
        if(isset($this->mongo_autoassign_log)){
            $data['message'] = $message;
            $data['create_time'] = date("Y-m-d h:i:s");
            $this->mongo_autoassign_log->insert($data);
        }
    }
    
    /*
     * 收到 web客户端 消息
     */
    function onMessage($server, $ws)
    {
        echo date('Y-m-d H:i:s').' On Message:= '.$ws->data."\n";
        $this->ws = $ws;
        $this->fd = $ws->fd;
        $this->data = $ws->data;
        $this->handleCommandMessage($server, $ws->data);

        return;
    }
    /*
     * 接受 client 消息
     */
    public function onReceive( swoole_server $server, $fd, $from_id, $data ) {
        echo date('Y-m-d H:i:s')." Get Message From Client {$fd}:{$data}\n";
        $this->handleCommandMessage($server, $data, $fd);
        
        return;
    }
    /*
     * 处理消息
     */
    public function handleCommandMessage($server,$data, $fd)
    {
        $data = $this->getCommand($data);
        $cmd = $data['cmd'];
        if($cmd == autoassign\ClientCommand::ALL_REDIS_ORDERS){
            $this->broadcastToSpecifiedClient($server, $fd, 'this handleCommandMessage from connect');
            echo 'handleCommandMessage message send';
            $orders = $this->getOrders();
            foreach($orders as $key => $order){
                if ($order['order_id']==null || $order['order_id']=='')
                {
                    continue;
                }
                $order = $this->getOrderStatus($order);
                $order['updated_at']=$order['created_at'];
                $d = json_encode($order);
                echo 'onConnect;d='.$d;
                $this->broadcastToSpecifiedClient($server, $fd, $msg);
            }
            return;
        }
        $nextStatus = autoassign\ClientCommand::START;//默认下一步是“开始自动派单”
        $currentStatus = (bool) json_decode($this->redis->get(REDIS_IS_SERVER_SUSPEND));
        echo 'currentStatus:'.$currentStatus;
        if(!$currentStatus){//如果当前是“开始自动派单”，则下一个状态为“停止自动服务”
            $nextStatus = autoassign\ClientCommand::STOP;
        }
        switch ($nextStatus) {
            case autoassign\ClientCommand::START:
            {
                echo date('Y-m-d H:i:s')." 服务继续\n";
                $this->isServerSuspend = false;
                $this->redis->set(REDIS_IS_SERVER_SUSPEND,json_encode(false));
                $this->broadcast($server, autoassign\ClientCommand::START);
            }
            break;
            case autoassign\ClientCommand::STOP:
            {
                echo date('Y-m-d H:i:s')." 服务暂停\n";
                $this->isServerSuspend = true;
                $this->redis->set(REDIS_IS_SERVER_SUSPEND,json_encode(true));
                $this->broadcast($server, autoassign\ClientCommand::STOP);
            }
            break;
            default:
                break;
        }
        return;
    }
    /*
     * 获取 Command
     */
    public function getCommand($data){
        $data = explode(',', $data);
        $d = array(
            'cmd' => $data[0],
            'fulltimeout_start' => 0,
            'fulltimeout_end' => $this->config['FULLTIME_WORKER_TIMEOUT'],
            'freetimeout_start' => 0,
            'freetimeout_end' => $this->config['FREETIME_WORKER_TIMEOUT'],
        );
        return $d;
    }
    /*
     * 启动定时器
     */
    public function startTimer($server) {
        echo date('Y-m-d H:i:s').' 启动定时任务,周期为 '.$this->config['TIMER_INTERVAL']. "秒\n";
        $this->serv = $server;
        $this->timer_id = $server->tick($this->config['TIMER_INTERVAL'] * 1000, function ($id) {
            $this->saveStatus($this->serv);
            $this->processOrders($this->serv);
        });
    }

    /*
     * 维持心跳
     */
    public function saveStatus($server){
        //echo 'saveStatus'."\n";
        $key = $this->config['_REDIS_SERVER_RUN_STATUS_'];

        $d = array('ip' => $this->serverIP(), 'port' => $this->config['SERVER_LISTEN_PORT'], 'time' => time());
        $data = json_encode($d);
        $this->redis->set($key, $data);
        if ($server)
        {
            //echo "save status broadcast...\n";
            $this->broadcast($server,'Assign Server is OK');
        }
    }
    /*
     * 处理订单
     */
    public function processOrders($server) {
        $isSuspend = (bool) json_decode($this->redis->get(REDIS_IS_SERVER_SUSPEND));
        if ($isSuspend==true)
        {
            return;
        }
        $this->isWorkerTaskRunning = true;
        echo date('Y-m-d H:i:s').' 正在获取订单===>';
        //取得订单启动任务foreach orders
        $orders = $this->getOrders();
        $count = count($orders);
        $n = 0;
        if ($count>0)
        {
            echo '有 '.$count.' 个订单待指派'."\n";
        }else{
            echo "没有待指派订单\n";
        }
        foreach($orders as $key => $order){
            
            if ($order['order_id']==null || $order['order_id']=='')
            {
                continue;
            }
            
            $order = $this->getOrderStatus($order);
            
            /*
             * TODO: 张旭刚
             * 需要判断订单的时间频率，而不是每次都去调API    -- by zhanghang
             * 
             * if 0-5分钟  call 推送全职
             * if 5-15分钟 call 推送兼职
             * if >15 分钟 call 人工指派
             */
            
            echo date('Y-m-d H:i:s').' 订单:＝ '. $order['order_id']." 派单中==>";
            $this->recordMessageByMongodb(date('Y-m-d H:i:s').' 订单:＝ '. $order['order_id']." 派单中");
            $isOK = false;
            
            $timerDiff = time() - (int)($order['assign_start_time']);

            echo '已过 '.$timerDiff.' 秒 ==>';
            
            if ( ($timerDiff < $this->config['FULLTIME_WORKER_TIMEOUT'] *60) && ($order['worker_identity']=='0'))
            {
                echo 'Order_ID:'.$order['order_id']." 0-5分钟，指派全职阿姨\n";
                $isOK = true;
            }
            else if ( ($timerDiff > $this->config['FULLTIME_WORKER_TIMEOUT']*60 && $timerDiff < $this->config['FREETIME_WORKER_TIMEOUT']*60 ) && ( $order['worker_identity']=='1' ))
            {
                echo 'Order_ID:'.$order['order_id']." 5-10分钟，指派兼职阿姨\n";
                $isOK = true;
            }
            else if ( $timerDiff > $this->config['SYSTEM_ASSIGN_TIMEOUT'] *60 )
            {
                echo 'Order_ID:'.$order['order_id']." 超过15分钟，转人工指派\n";
                $isOK = true;
            }
            if ($isOK)
            {
                //推送到客户端
//                $this->broadcast($server,$d);
                $this->serv->task($order);
            }

            $n++;
            if($n > $count){break;}
        }
        $this->isWorkerTaskRunning = false;
    }
    /*
     * 获取订单状态
     */
    public function getOrderStatus($order) {
        //echo 'getOrderStatus' . "\n";

        if ($order['worker_identity'] == '0') {
            $order['status'] = '1';
        } else if ($order['worker_identity'] == '1') {
            $order['status'] = '2';
        } else if ($order['worker_identity'] == '2') {
            $order['status'] = '1001';
        }

        return $order;
    }

    /*
     * 获取待指派订单
     */
    public function getOrders(){
        $orders = $this->redis->zrange($this->config['_REDIS_WAIT_ASSIGN_ORDER_POOL_'],0,-1);
        foreach($orders as $key => $value){
            $orders[$key] = (array)json_decode($value);
        }
        return (array)$orders;
    }
    /*
     * 客户端连接时触发
     */
    public function onConnect($server, $fd) {
        echo date('Y-m-d H:i:s').' '.$fd."Client Connect.\n";
        return true;
    }
    /*
     * 客户端断开时触发
     */
    public function onClose($server, $client_id, $from_id) {
        echo date('Y-m-d H:i:s')." Client Close.\n";
        try{
            parent::onClose($server, $client_id, $from_id);
        } catch (Exception $ex) {
            echo date('Y-m-d H:i:s').$ex->getMessage();
        }
    }
    /*
     * 耗时任务在taskworker中执行
     */
    public function onTask($server, $task_id, $from_id, $data) {
        //echo 'onTask'."\n";
        $this->serv = $server;
        echo 'task start time :'.time();
        echo "当前服务器主进程的PID:".$server->master_pid."</br>";
        echo "当前服务器管理进程的PID:".$server->manager_pid."</br>";
        echo "当前Worker进程的编号:".$server->worker_id."</br>";
        echo '$from_id is '.$from_id.'; task_id is '.$task_id." called"."</br>";
        //return $this->taskOrder($data, $server);
        echo '当前任务订单数据为:'.$data;
        return $this->taskOrder($server, $data);
    }
    
    /*
     * 调用 BOSS API 指派阿姨
     */
    public function taskOrder($server, $data) {
        echo date('Y-m-d H:i:s') . ' 请求API' . $this->config['BOSS_API_URL'] . $data['order_id'] . "\n";
        $url = $this->config['BOSS_API_URL'] . $data['order_id'];
        try {
            $result = @file_get_contents($url);
            echo '指派结果为:'.$result;
            $this->recordMessageByMongodb('指派结果为:'.$result);
            $d = json_decode($result,true);
            if(isset($d['order_code'])){
                $d['created_at'] = date('Y-m-d H:i:s', $d['created_at']);
                $d['assign_start_time'] = isset($d['assign_start_time'])?date('Y-m-d H:i:s', $d['assign_start_time']) : '';
                $d['updated_at'] = isset($d['updated_at']) ? date('Y-m-d H:i:s', $d['updated_at']) : '';
                $d = json_encode($d);
                $this->broadcast($server,$d);
            }
        } catch (Exception $ex) {
            echo date('Y-m-d H:i:s').$ex->getMessage()."\n";
            var_dump($data);
            echo $ex->getTrace()."\n";
        } 
        return $data;
    }
    /*
     * 任务完成时触发
     */
    public function onFinish($server,$task_id, $data) {
        echo date('Y-m-d H:i:s').'订单:＝ '.$data['order_id']." 本次任务完成\n";
        echo "当前Worker进程的编号:".$server->worker_id."</br>";
        echo '; task_id is '.$task_id." called"."</br>";
        echo '任务结束时间为:'.time();
//        $d = $data;
//        if(isset($d['order_id'])) {
//            unset($d['order_id']);
//        }
//        $d['created_at'] = date('Y-m-d H:i:s', $d['created_at']);
//        $d['assign_start_time'] = date('Y-m-d H:i:s', $d['assign_start_time']);
//        $d['updated_at'] = isset($d['updated_at']) ? date('Y-m-d H:i:s', $d['updated_at']) : '';
//        $d = json_encode($d);
//        $this->broadcast($server,$d);
    }
    /*
     * 广播给所有客户端
     */
    public function broadcast($server, $msg)
    {
        $msg = json_encode($msg);
        foreach ($server->connections as $clid => $info)
        {
            echo 'clid='.$clid.';msg='.$msg;
            $this->broadcastToSpecifiedClient($server,$clid, $msg);
        }
    }
    
    public function broadcastToSpecifiedClient($server,$clid, $msg){
        try{
            
                $server->push($clid, $msg);
            } catch (Exception $ex) {
                echo date('Y-m-d H:i:s').$ex->getMessage();
            }
    }
    
    /**
     * 配置文件操作(查询与修改)
     * 默认没有第三个参数时，按照字符串读取提取''中或""中的内容
     * 如果有第三个参数时为int时按照数字int处理。
     * 调用demo
      $name="admin";//kkkk
      $bb='234';
      $bb=getconfig("./config.php", "bb", "string");
      updateconfig("./2.php", "name", "admin");
     */
    function get_config($file, $ini, $type = "string") {
        if (!file_exists($file)){
            echo "file not exist\n";
            return false;
        }
        $str = file_get_contents($file);
        if ($type == "int") {
            $config = preg_match("/" . preg_quote($ini) . "=(.*);/", $str, $res);
            return $res[1];
        } else {
            $config = preg_match("/" . preg_quote($ini) . "=\"(.*)\";/", $str, $res);
            if ($res[1] == null) {
                $config = preg_match("/" . preg_quote($ini) . "='(.*)';/", $str, $res);
            }
            return $res[1];
        }
    }
    /*
     * 配置文件更新
     */
    function update_config($file, $name, $value, $type = "string") {
        if (!file_exists($file)) {
            echo "file not exist\n";
            return false;
        }
        $srcConfigContent = file_get_contents($file);
        $newConfigContent = "";
        if ($type == "int") {
            $newConfigContent = preg_replace("/" . preg_quote($name) . "=>(.*);/", $name . "=>" . $value . ";", $srcConfigContent);
        } else {
            $rex = "#.\'".preg_quote($name)."\' => *[1-9]\d*.#";
            $replace = " '".preg_quote($name)."' => ".$value.",";
            $newConfigContent = preg_replace($rex,$replace,$srcConfigContent);
            //echo $newConfigContent;
        }
        file_put_contents($file, $newConfigContent);
    }

}
/*
 * 启动服务
 */
$server = new server($assign_config);
