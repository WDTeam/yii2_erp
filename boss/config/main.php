<?php
$params = array_merge(
    require(__DIR__ . '/../../dbbase/config/params.php'),
    require(__DIR__ . '/../../dbbase/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-boss',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'boss\controllers',
    'bootstrap' => ['log'],
    'modules' => [
//        'blog' => [
//            'class' => 'funson86\blog\Module',
//            'controllerNamespace' => 'funson86\blog\controllers\boss'
//        ],
        'dynagrid'=> [
            'class'=>'\kartik\dynagrid\Module',
        ],
        'gridview' => [
            'class' => 'kartik\grid\Module',
        ],
    ],
    'components' => [
        'user' => [
            'identityClass' => 'core\models\general\SystemUser',
            'enableAutoLogin' => true,
        ],
        
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'enableStrictParsing' => true,
            'rules' => [
                ''=>'general/site/index',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'general/site/error',
        ],
        'areacascade' => [
            'class' => 'boss\components\AreaCascade'
        ],
    ],
    'params' => $params,
];
