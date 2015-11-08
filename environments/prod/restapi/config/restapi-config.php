<?php
$config = [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@api' => '@app',
    ],
    'controllerNamespace' => 'restapi\controllers',
    'modules' => [
//         'user' => [
//             'class' => 'api\modules\user\Module',
//         ],
    ],
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
//            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                'v<version:\d>/<controller:\w+>/<id:\d+>' => '<controller>/view',
                'v<version:\d>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                'v<version:\d>/<controller:\S+>/<action:\S+>' => '<controller>/<action>',
                //'v<version:\d>/<controller:\S+>' => '<controller>',
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'user-info',
                    'except' => ['delete'],
                ],
            ],
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
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,//这句一定有，false发送邮件，true只是生成邮件在runtime文件夹下，不发邮件
            'viewPath' => 'dbbase/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.163.com',
                'username' => 'linuu90@163.com',
                'password' => 'uu801272',
                'port' => '25',
                'encryption' => 'tls',

            ],
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => ['linuu90@163.com' => 'APIForDevelopLocalhost']
            ],
        ],
    ]
];

return $config;
