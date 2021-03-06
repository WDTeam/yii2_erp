<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'prod');
ini_set("display_errors", "off");//api屏蔽所有错误信息
require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../dbbase/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../dbbase/config/dbbase-config.php'),
    require(__DIR__ . '/../../restapi/config/restapi-config.php')

);

$application = new yii\web\Application($config);
$application->run();
