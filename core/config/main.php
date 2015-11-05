<?php

$config =  [
    'id' => 'app-core',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['core\components\EventBind','log'],
    'aliases'=>[
        '@core'=>'@app',
    ],
    'controllerNamespace' => 'core\controllers',
    'modules' => [
    ],
    'components' => [
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' =>true,//这句一定有，false发送邮件，true只是生成邮件在runtime文件夹下，不发邮件
            'viewPath' => 'dbbase/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.163.com',
                'username' => 'linuu90@163.com',
                'password' => 'uu801272',
                'port' => '25',
                'encryption' => 'tls',
        
            ],
            'messageConfig'=>[
                'charset'=>'UTF-8',
                'from'=>['linuu90@163.com'=>'CoreServiceForLocalhost']
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
//            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'user/user-info',
                    'except' => ['delete'],
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'dbbase\models\UserInfo',
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
                    'categories' => ['service-user-post'],
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
    'params' =>  [
    'restapi'=>['tokenExpire'=>3600*24],
    'appkeys'=>[
        'ios'=>'ejiajie_ios_v1',
        'crm'=>'ejiajie_crm_v1',
        'android'=>'ejiajie_android_v1',
        'sdk'=>'ejiajie_sdk_v1',
        'docs'=>'ejiajie_docs_v1',
        'docs_api'=>'ejiajie_api_docs_v1'
    ],
],
];
if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'on beforeAction' => function($event) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
        },
        ];
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'on beforeAction' => function($event) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
        },
        ];
}


return $config;

