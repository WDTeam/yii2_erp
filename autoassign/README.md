# 系统简介

　　BOSS自动派单服务实例<br/><br/>

## 部署说明：

```
	1、本程序运行在 linux 或 windows 环境，需要 PHP 5.4+ / swoole扩展 / redis 扩展
	2、手动输入命令 php autoassign-server.php 启动；
        3、自动启动: 在linux系统的 crontab 中加入： */1 * * * * ./startup.sh  （TODO：升级为可程序控制）
	4、浏览器打开BOSS系统 -> 订单管理 -> 智能派单-> "连接派单服务器"
        5、界面上提示 “连接成功”
        6、点击“停止派单”-> 暂停派单 点击“开始派单”-> 继续派单
        7、修改全职、兼职的派单时间，点击“更新配置”-> 实时生效

```

## 配置说明：

```
    1、配置文件： autoassign.config.php
    2、配置内容：
    /*
     * Redis 服务IP
     */
    'REDIS_SERVER_IP' => '101.200.179.70',
    /*
     * Redis 服务PORT
     */
    'REDIS_SERVER_PORT '=> 6379,
    /*
     * Redis 服务运行状态
     */
    '_REDIS_SERVER_RUN_STATUS_' => '_SWOOLE_SOCKET_RUN_STATUS_',
    /*
     * Redis 待指派订单池
     */
    '_REDIS_WAIT_ASSIGN_ORDER_POOL_ '=> 'WaitAssignOrdersPool',
    /*
     * 服务监听地址（默认 0.0.0.0 勿改）
     */
    'SERVER_LISTEN_IP' => '0.0.0.0',
    
    /*
     * 服务监听端口（默认 9501）
     */
    'SERVER_LISTEN_PORT' => 9501,

```
## TODO LIST：

```
	1、请在BOSS订单模块，智能指派接口部分，引入 common\autoassign.config.php 使用里面定义好的配置项
	2、请在BOSS订单模块，智能指派页面，引入 common\models\autoassign\ClientCommand; 枚举控制台命令，传递数据结构应包含 cmd 项
	3、自动热启动（不用重启服务以更新配置项）
	4、BOSS控制台页面的控制部分（请找人写）
```