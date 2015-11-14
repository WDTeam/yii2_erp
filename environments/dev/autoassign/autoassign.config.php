<?php
/*
 * BOSS 自动派单运行服务配置 For Local
 * @author 张航<zhanghang@1jiajie.com>
 * @link http://boss.1jiajie.com/auto-assign/
 * @copyright Copyright (c) 2015 E家洁 LLC
 */
return [
    /*
     * 自定义主服务进程名ID
     */
    'SERVER_MASTER_PROCESS_ID' => 'autoassign-server-master',
    /*
     * 自定义 WORKER-TASK 服务进程名ID前缀
     */
    'SERVER_WORKER_PROCESS_ID' => 'autoassign-server-worker-id-',
    /*
     * Redis 服务IP（推荐使用域名）
     */
    'REDIS_SERVER_IP' => 'deb09fcb1b404eb6.m.cnbja.kvstore.aliyuncs.com',
     /*
     * Redis 服务PORT
     */
    'REDIS_SERVER_PORT' => 6379,
    /*
     * Redis 服务password
     */
    'REDIS_SERVER_PASSWORD' => 'deb09fcb1b404eb6:Ejiajie2015dev',
    
    /*
     * mongodb的URI
     */
    'MONGODB_SERVER' => 'mongodb://dev_boss_db_dbo:dev_boss@dev.boss.1jiajie.com:27017',
    
    /*
     * mongodb的数据库
     */
    'MONGODB_SERVER_DB_NAME' => 'dev_boss_db',
    
    /*
     * Redis 服务运行状态
     */
    '_REDIS_SERVER_RUN_STATUS_' => '_REDIS_SERVER_RUN_STATUS_',
    /*
     * Redis 待指派订单池
     */
    '_REDIS_WAIT_ASSIGN_ORDER_POOL_' => 'WAIT_ASSIGN_ORDERS_POOL',
    /*
     * 服务监听地址（默认 0.0.0.0 勿改）
     */
    'SERVER_LISTEN_IP' => '0.0.0.0',

    /*
     * 服务监听端口（默认 9501）
     */
    'SERVER_LISTEN_PORT' => 9501,

    /*
     * SWOOLE 服务IP（推荐使用域名）
     */
    'SWOOLE_SERVER_IP' => 'dev.boss.1jiajie.com',

    /*
     * 设置启动的worker进程数
     * 业务代码是全异步非阻塞的，这里设置为CPU的1-4倍最合理
     * 业务代码为同步阻塞，需要根据请求响应时间和系统负载来调整
     * 比如1个请求耗时100ms，要提供1000QPS的处理能力，那必须配置100个进程或更多。
     * 但开的进程越多，占用的内存就会大大增加，而且进程间切换的开销就会越来越大。
     * 所以这里适当即可。不要配置过大。
     */
    'WORKER_NUM' => 2,

    /*
     * 配置task进程的数量
     */
    'TASK_WORKER_NUM' => 8,

    /*
     * 守护进程化
     * 设置daemonize => 1时，程序将转入后台作为守护进程运行。长时间运行的服务器端程序必须启用此项。
     * 如果不启用守护进程，当ssh终端退出后，程序将被终止运行。
     * 启用守护进程后，标准输入和输出会被重定向到 log_file
     * 如果未设置log_file，将重定向到 /dev/null，所有打印屏幕的信息都会被丢弃
     */
    'DAEMONIZE' => true,

    /*
     * 日志文件
     */
    'LOG_FILE' => '/code/ejj-enterprise-boss/autoassign/autoassign-server.log',

    /*
     * 设置worker进程的最大任务数
     */
    'MAX_REQUEST' => 1000,

    // 定时器 单位秒
    'TIMER_INTERVAL' => 6,

    // 全职阿姨 0-5分钟
    'FULLTIME_WORKER_TIMEOUT' => 5,

    // 兼职阿姨 5-15分钟
    'FREETIME_WORKER_TIMEOUT' => 15,

    // 超时人工指派 >15分钟
    'SYSTEM_ASSIGN_TIMEOUT' => 15,

    // 配置 BOSS API 地址
    'BOSS_API_URL' => 'http://dev.api.1jiajie.com/order/push/',

];