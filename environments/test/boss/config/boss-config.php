<?php
$config = [
    'id' => 'app-boss',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'boss\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'dynagrid' => [
            'class' => '\kartik\dynagrid\Module',
        ],
        'gridview' => [
            'class' => 'kartik\grid\Module',
        ],
    ],
    'components' => [
        'user' => [
            'identityClass' => 'core\models\system\SystemUser',
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
                '' => 'system/site/index',
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
                    'levels' => ['error'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'system/site/error',
        ],
        'areacascade' => [
            'class' => 'boss\components\AreaCascade'
        ],
    ],
];
return $config;