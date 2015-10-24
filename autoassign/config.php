<?php
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
// 配置 API地址
define('BOSS_API_URL','http://dev.api.1jiajie.com/order/push/');