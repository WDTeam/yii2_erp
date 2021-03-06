<?php

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],

    'controllerNamespace' => 'console\controllers',
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'templateFile' => '@jamband/schemadump/template.php',
        ],
        'schemadump' => [
            'class' => 'jamband\schemadump\SchemaDumpController',
        ],
    ],
    'params' => [],
];
