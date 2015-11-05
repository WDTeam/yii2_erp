<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../dbbase/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../dbbase/config/dbbase-config.php'),
    require(__DIR__ . '/../../restapi/config/restapi-config.php')
<<<<<<< HEAD

=======
>>>>>>> 64bd93095440106235fc270d5b85873aadbbc967
);
$application = new yii\web\Application($config);
$application->run();
