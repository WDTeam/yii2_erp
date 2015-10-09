<?php

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases'=>[
        '@api'=>'@app',
    ],
    'controllerNamespace' => 'api\controllers',
    'modules' => [
        'user' => [
            'class' => 'api\modules\user\Module',
        ],
    ],
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => false,
//            'enableStrictParsing' => true,
            'showScriptName' => false,
        ],
        'user' => [
            'identityClass' => 'common\models\UserInfo',
            'enableAutoLogin' => true,
            'enableSession' => false,
            'loginUrl' => null,
        ],

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'ZMtQdfNPstwSBet6Kq11PxRnRHU8Qq6d',
        ],
        'response' => [
            'format' => \yii\web\Response::FORMAT_JSON, //make json as default format
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                'file' => [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['trace'],
                    'categories' => ['yii\*'],
                ],
                'db' => [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['info'],
                    'categories' => ['api-post'],
                ],
                'email' => [
                    'class' => 'yii\log\EmailTarget',
                    'levels' => ['error', 'warning'],
                    'message' => [
                        'to' => ['linhongyou@1jiajie.com'],
                        'subject' => 'Exception Message',
                    ],
                ],
            ],
        ],


    ],
    'params' => include 'params-local.php',
];
