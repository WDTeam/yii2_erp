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

require('./config.php');

class server
{
    private $serv;
    private $tasks;
    private $data;
    private $ws;
    private $redis;
    private $ip = '0.0.0.0';
    private $port = 9501;
    private $isRun = true;
 
    /**
     * [__construct description]
     * 构造方法中,初始化 $serv 服务
     */
    public function __construct() {
        $this->connectRedis();
        $this->saveStatus();
        $workerTaskIsRunning=false;
        $this->serv = new swoole_websocket_server($this->ip, $this->port);
        //初始化swoole服务
        $this->serv->set(array(
            'worker_num'  => 8,
            'daemonize'   => false,
            'max_request' => 1000,
            'log_file'    => './swoole.log',
            'task_worker_num' => 8,
            
        ));

        //设置监听
        $this->serv->on('Start', array($this, 'onStart'));
        $this->serv->on('Connect', array($this, 'onConnect'));
        $this->serv->on("Close", array($this, 'onClose'));
        $this->serv->on("Task", array($this, 'onTask'));
        $this->serv->on("Finish", array($this, 'onFinish'));
        $this->serv->on("Message", array($this, 'onMessage'));

        //开启 
        $this->serv->start();
    }
    /*
     * 获取本机IP
     */
    public function serverIP(){   
        $ss = exec('/sbin/ifconfig eth0 | sed -n \'s/^ *.*addr:\\([0-9.]\\{7,\\}\\) .*$/\\1/p\'',$arr);
        $ret = $arr[0];
        return $ret;
    }
    /*
     * 连接 Redis
     */
    public function connectRedis(){
        $this->redis = new Redis();
        $this->redis->connect(REDIS_IPADDRESS, REDIS_IP_PORT);
    }
    /*
     * Server 启动
     */
    public function onStart($server) {
        echo SWOOLE_VERSION . " onStart\n";
        return true;
    }
    /*
     * 获取配置参数
     */
    public function getParams($ws){
        $data = explode(',', $ws->data);
        $d = array(
            'interval' => TIMER_INTERVAL,
            'taskName' => $data[1],
            'theadnum' => $data[2],
            'qstart' => $data[3],
            'qend' => $data[4],
            'jstart' => $data[5],
            'jend' => $data[6],
            'isRun' => $data[7],
        );
        $this->isRun = $d['isRun'];
        return $d;
    }
    /*
     * 收到客户端消息
     */
    function onMessage($server, $ws)
    {
        $data = $this->getParams($ws);
        $this->startTimer($server, $data, $ws);
        return;
    }
    /*
     * 启动定时器
     */
    public function startTimer($server,$data, $ws)
    {   
        echo 'startTimer'."\n";
        $this->serv = $server;
        $this->data = $data;
        $this->ws = $ws;

        swoole_timer_add( TIMER_INTERVAL*1000, function ($interval) {
            if($this->isRun){
                $this->saveStatus();
                $this->processOrders($this->serv, $this->data, $this->ws);
            }
        });
    }
    /*
     * 维持心跳
     */
    public function saveStatus(){
        echo 'saveStatus'."\n";
        $key = '_SWOOLE_SOCKET_RUN_STATUS_';
        $d = array('ip' => $this->serverIP(), 'port' => $this->port, 'time' => time());
        $data = json_encode($d);
        $this->redis->set($key, $data);
    }
    /*
     * 处理订单
     */
    public function processOrders($server, $data, $ws) {
        echo 'processOrders'."\n";
       
        $isRunning = $this->redis->get('TIMER_PROCESS_IS_RUNNING');
        
        //取得订单启动任务foreach orders
        $orders = $this->getOrders();
        var_dump($orders);
        $count = count($orders);
        $n = 0;
        if ($count>0)
        {
            echo '有 '.$count.' 个订单待指派'."\n";
            var_dump($orders);
        }
        foreach($orders as $key => $order){
            $order = $this->getOrderStatus($order, $data);

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
            
            if ( ($timerDiff < FULLTIME_WORKER_TIMEROUT*60) && ($order['worker_identity']=='0'))
            {
                echo 'Order_ID:'.$order['order_id'].' 0-5分钟，指派全职阿姨\n';
                $isOK = true;
            }
            else if ( ($timerDiff > FULLTIME_WORKER_TIMEROUT*60 && $timerDiff < FREETIME_WORKER_TIMEROUT*60 ) && ( $order['worker_identity']=='1' ))
            {
                echo 'Order_ID:'.$order['order_id'].' 5-10分钟，指派兼职阿姨\n';
                $isOK = true;
            }
            else if ( $timerDiff > ASSIGN_TIMEOUT*60 )
            {
                echo 'Order_ID:'.$order['order_id'].' 超过15分钟，转人工指派\n';
                $isOK = true;
            }
            if ($isOK)
            {
                //进行推送
                if(!empty($ws)){$server->push($ws->fd, $d);}
                $this->serv->task($order);
            }

            $n++;
            if($n > $count){break;}
        }
        $workerTaskIsRunning = false;
    }
    /*
     * 订单状态
     */
    public function getOrderStatus($order, $data){
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
        $orders = $this->redis->zrange('WAIT_ASSIGN_ORDERS_POOL',0,-1);
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
       
        var_dump($data);
        return $this->taskOrder($data, $server);
        
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
        $url = BOSS_API_URL.$data['order_id'];
//        $url = 'http://api.me/order/push/'.$data['order_id'];
        $result = @file_get_contents($url);
        //$data = (array)json_decode($d);
        //var_dump($data);
        return $data;
    }
    /*
     * 任务完成时触发
     */
    public function onFinish($server,$task_id, $data) {
        echo "onFinish\n";
        
//        $data['created_at'] = date('Y-m-d H:i:s', $data['created_at']);
        $data['updated_at'] = isset($data['updated_at']) ? date('Y-m-d H:i:s', $data['updated_at']) : '';

          $d = json_encode($data);
//        var_dump($d);
        echo 'end:'. $data['order_id']."\n";
        $server->push($this->ws->fd, $d);
        $this->broadcast($server,$d);
    }
    /*
     * 广播给所有客户端
     */
    public function broadcast($server, $msg)
    {
        $msg = json_encode($msg);
        foreach ($this->serv->connections as $clid => $info)
        {
            $this->serv->send($clid, $msg);
        }
    }
}
/*
 * 启动服务
 */
$server = new server();
