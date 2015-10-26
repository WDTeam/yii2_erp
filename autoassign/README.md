# 系统简介

　　BOSS自动派单服务实例<br/><br/>

## 部署说明：

```
	1、本程序运行在 linux 或 windows 环境，需要 PHP 5.4+ / swoole扩展 / redis 扩展
	2、手动输入命令 PHP server.php 启动；
        3、自动启动: 在linux系统的crontab中加入： */1 * * * * ./startup.sh
	4、浏览器打开BOSS系统 -> 订单管理 -> 智能派单-> "连接派单服务器"
        5、界面上提示 “自动派单开始”

```

## 配置说明：

```
    1、配置文件： config.php
    2、配置内容：
        // 定时器 单位秒
        define('TIMER_INTERVAL',6);

        // 全职阿姨 5分钟
        define('FULLTIME_WORKER_TIMEROUT',5);

        // 兼职阿姨 5分钟
        define('FREETIME_WORKER_TIMEROUT',15);

        // 超时人工指派 15分钟
        define('ASSIGN_TIMEOUT',15);

        // 配置 Redis IP地址（推荐使用域名）
        define('REDIS_IPADDRESS','101.200.179.70');

        // 配置 Redis IP端口
        define('REDIS_IP_PORT',6379);

```