<?php
/*
 * BOSS 自动派单运行服务实例
 * @author 张旭刚<zhangxugang@corp.1jiajie.com>
 * @author 林洪优<linhongyou@1jiajie.com>
 * @link http://boss.1jiajie.com/auto-assign/
 * @copyright Copyright (c) 2015 E家洁 LLC
*/
define('DEBUG', 'on');
define("WEBPATH", str_replace("\\","/", __DIR__));
define("CONFIG_PATH", WEBPATH."/autoassign.config.php");

$assign_config = require(CONFIG_PATH);

use autoassign\ClientCommand;

class server
{
    private $serv;
    private $ws;
    private $fd;
    private $data;
    private $redis;
    private $isServerSuspend = false;
    private $isWorkerTaskRunning = false;
    private $config;
    private $timer_id;
 
    /**
     * [__construct description]
     * 构造方法中,初始化 $serv 服务
     */
    public function __construct($config) {
        echo "自动指派服务启动中";
        $this->config = $config;
        $this->connectRedis();
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
        
        //开启 
        $this->serv->start();
    }
    /*
     * Server 启动
     */
    public function onStart($server) {
        echo "==>已启动\n";
        echo "主进程ID：= " .$this->config['SERVER_MASTER_PROCESS_ID']."\n";
        cli_set_process_title($this->config['SERVER_MASTER_PROCESS_ID']);
    }

    /*
     * WorkerStart 启动
     */
    function onWorkerStart(swoole_server $server, $worker_id) {
        //echo 'onWorkStart ID:=' . $worker_id . "\n";
        cli_set_process_title($this->config['SERVER_WORKER_PROCESS_ID'] . $worker_id);

        // 只有当worker_id为0时才添加定时器,避免重复添加
        if ($worker_id == 0) {
            $workerProcessNum = $this->config['WORKER_NUM']+$this->config['TASK_WORKER_NUM'];
            echo '工作进程ID:= '.$this->config['SERVER_WORKER_PROCESS_ID']." 已启动 ".$workerProcessNum." 进程\n"; 
            $this->config = require(CONFIG_PATH);
            $this->startTimer($server);
        }
    }
    /*
     * Worker Stop
     */
    function onWorkerStop($server, $worker_id) {
        echo 'Worker Stop && Reload...'."\n";
        //opcache_reset(); //zend_opcache的      
        //apc, xcache, eacc等其他方式，请调用相关函数  
    }

    /*
     * 获取本机IP
     */
    public function serverIP(){
        $localIpArray = swoole_get_local_ip();
        $localIp = $localIpArray[0];

        return $localIp;
    }
    /*
     * 连接 Redis
     */
    public function connectRedis(){
        $this->redis = new Redis();
        $this->redis->connect($this->config['REDIS_SERVER_IP'], $this->config['REDIS_SERVER_PORT']);
    }
    /*
     * 收到 web客户端 消息
     */
    function onMessage($server, $ws)
    {
        echo 'On Message'."\n";

        $this->handleCommandMessage($server, $ws);

        return;
    }
    /*
     * 接受 client 消息
     */
    public function onReceive( swoole_server $server, $fd, $from_id, $data ) {
        echo "Get Message From Client {$fd}:{$data}\n";
        $this->handleCommandMessage($server, $ws);
        
        return;
    }
    /*
     * 处理消息
     */
    public function handleCommandMessage($server,$ws)
    {
        $this->ws = $ws;
        $this->fd = $ws->fd;
        $this->data = $ws->data;
        $data = $this->getCommand($ws);
        $cmd = $data['cmd'];
        
        switch ($cmd) {
            case ClientCommand::START:
            {
                $this->isServerSuspend = false;
            }
            break;
            case ClientCommand::STOP:
            {
                $this->isServerSuspend = true;
            }
            break;
            case ClientCommand::RELOAD:
            {
                $server->reload();
            }
            break;
            case ClientCommand::UPDAE:
            {
                
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
    public function getCommand($ws){
        $data = explode(',', $ws->data);
        $d = array(
            'cmd' => $data[0],
            'qstart' => $data[3],
            'qend' => $data[4],
            'jstart' => $data[5],
            'jend' => $data[6],
        );
        return $d;
    }
    /*
     * 启动定时器
     */
    public function startTimer($server) {
        echo '启动定时任务,周期为 '.$this->config['TIMER_INTERVAL']. "秒\n";
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
            $this->broadcast($server,'Assign Server is OK');
        }
    }
    /*
     * 处理订单
     */
    public function processOrders($server) {
        if ($this->isServerSuspend)
        {
            return;
        }
       
        $this->isWorkerTaskRunning = true;
        echo '正在获取订单===>';
        //取得订单启动任务foreach orders
        $orders = $this->getOrders();
        //var_dump($orders);
        $count = count($orders);
        $n = 0;
        if ($count>0)
        {
            echo '有 '.$count.' 个订单待指派'."\n";
            //var_dump($orders);
        }else{
            echo "没有待指派订单\n";
        }
        foreach($orders as $key => $order){
            $order = $this->getOrderStatus($order);

            $d = $order;
            $d['created_at'] = date('Y-m-d H:i:s', $d['created_at']);
            $d['updated_at'] = isset($d['updated_at']) ? date('Y-m-d H:i:s', $d['updated_at']) : '';
            $d = json_encode($d);
            
            /*
             * TODO: 张旭刚
             * 需要判断订单的时间频率，而不是每次都去调API    -- by zhanghang
             * 
             * if 0-5分钟  call 推送全职
             * if 5-15分钟 call 推送兼职
             * if >15 分钟 call 人工指派
             */
            
            echo 'start:::'. $order['order_id']."\n";
            $isOK = false;
            
            $timerDiff = time() - (int)($order['created_at']);

            echo '已过 '.$timerDiff.' 秒.';
            
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
                $this->broadcast($server,$d);
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
    public function getOrderStatus($order){
        echo 'getOrderStatus'."\n";
        
            if ($order['worker_identity']=='0')
            {
                $order['status'] = '1';
            }
            else if ($order['worker_identity']=='1')
            {
                $order['status'] = '2';
            }
            else if ($order['worker_identity']=='2')
            {
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
            // 加锁与解锁
            if(isset($value['lock']) && $value['lock']){
                unset($orders[$key]);
                break;
            }
            $orders[$key] = (array)json_decode($value);
        }
        return (array)$orders;
    }
    /*
     * 客户端连接时触发
     */
    public function onConnect($server, $fd) {
        echo $fd."Client Connect.\n";
        return true;
    }
    /*
     * 客户端断开时触发
     */
    public function onClose($server, $fd) {
        echo "Client Close.\n";
    }
    /*
     * 多线程任务
     */
    public function onTask($server, $task_id, $from_id, $data) {
        echo 'onTask'."\n";
        $this->serv = $server;
       
        //return $this->taskOrder($data, $server);
        
        if ($data['lock']==false)
        {
            $this->lockOrder($data);//加入状态锁
            return $this->taskOrder($data, $server);
        }
    }
    /*
     * 锁订单，避免重复处理
     */
    public function lockOrder($order){
        echo 'lockOrder';
        $order['lock'] = true;
        $this->redis->zadd($order);
    }
    /*
     * 调用 BOSS API 指派阿姨
     */
    public function taskOrder($data){
        echo 'taskOrder'."\n";
        $url = $this->config['BOSS_API_URL'].$data['order_id'];
        $result =  @file_get_contents($url);
        //$data = (array)json_decode($d);
        //var_dump($data);
        return $data;
    }
    /*
     * 任务完成时触发
     */
    public function onFinish($server,$task_id, $data) {
        echo 'OrderID:'.$data['order_id']." on Finish\n";
        $data['updated_at'] = isset($data['updated_at']) ? date('Y-m-d H:i:s', $data['updated_at']) : '';
        $d = json_encode($data);
        $this->broadcast($server,$d);
    }
    /*
     * 广播给所有客户端
     */
    public function broadcast($server, $msg)
    {
        $msg = json_encode($msg);
        foreach ($server->connections as $clid => $info)
        {
            $server->push($clid, $msg);
        }
    }
    /**
     * 配置文件操作(查询与修改)
     * 默认没有第三个参数时，按照字符串读取提取''中或""中的内容
     * 如果有第三个参数时为int时按照数字int处理。
     * 调用demo
      $name="admin";//kkkk
      $bb='234';
      $bb=getconfig("./2.php", "bb", "string");
      updateconfig("./2.php", "name", "admin");
     */
    function get_config($file = CONFIG_PATH, $ini, $type = "string") {
        if (!file_exists($file))
            return false;
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
    function update_config($file = CONFIG_PATH, $ini, $value, $type = "string") {
        if (!file_exists($file))
            return false;
        $str = file_get_contents($file);
        $str2 = "";
        if ($type == "int") {
            $str2 = preg_replace("/" . preg_quote($ini) . "=(.*);/", $ini . "=" . $value . ";", $str);
        } else {
            $str2 = preg_replace("/" . preg_quote($ini) . "=(.*);/", $ini . "=\"" . $value . "\";", $str);
        }
        file_put_contents($file, $str2);
    }
}
/*
 * 启动服务
 */
$server = new server($assign_config);
