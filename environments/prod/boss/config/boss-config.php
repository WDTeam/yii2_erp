<?php

$config = [
    'id' => 'app-boss',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'boss\controllers',
    'bootstrap' => ['log'],
    'modules' => [
//        'blog' => [
//            'class' => 'funson86\blog\Module',
//            'controllerNamespace' => 'funson86\blog\controllers\boss'
//        ],
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
                    'levels' => ['error', 'warning'],
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

if (!YII_DEBUG) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii']['class'] = 'yii\gii\Module';
    //Add this into backend/config/main-local.php
    $config['modules']['gii']['generators'] = [
        'kartikgii-crud' => ['class' => 'warrence\kartikgii\crud\Generator'],
    ];
}
function dump($_data)
{
    echo '<pre>';
    print_r($_data);
    echo '</pre>';
}

return $config;