<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
ini_set("display_errors", "on");//生产环境屏蔽所有错误信息
error_reporting(E_ALL);//boss后台将各级别错误都提示出来
require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../dbbase/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../dbbase/config/dbbase-config.php'),
    require(__DIR__ . '/../../boss/config/boss-config.php')
);

$application = new yii\web\Application($config);
$application->run();
