<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');
<<<<<<< HEAD
ini_set("display_errors","off");
error_reporting(0);
=======
ini_set("display_errors", "on");
error_reporting(E_ALL);
>>>>>>> 03ccb5e1173694cd3a45fadcca0b37ecd266753b
require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../dbbase/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../dbbase/config/dbbase-config.php'),
    require(__DIR__ . '/../../boss/config/boss-config.php')
);

$application = new yii\web\Application($config);
$application->run();
