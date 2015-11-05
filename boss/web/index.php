<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
ini_set("display_errors","on");
error_reporting(E_ALL);
require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../dbbase/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');
require(__DIR__ . '/../config/dbbase-local.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../dbbase/config/main.php'),
  
    require(__DIR__ . '/../config/main.php')
  
);

$application = new yii\web\Application($config);
$application->run();
