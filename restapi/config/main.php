<?php
$params = [
'uploadpath' =>true, //true上传到七牛 false 上传的本地
'worker_base_salary'=>3000,//阿姨的底薪
'unit_order_money_nonself_fulltime' =>50,//小家政全时段阿姨补贴的每单的金额
'order_count_per_week'=>12,//小家政全时段阿姨的底薪策略是保单，每周12单
 'service'=>[
        'user'=>[
            'domain'=>'http://dev.service.1jiajie.com:80/'
        ]
    ]
];
$ErrorCode = [
    '100001'=>'数据传入，为空',
    '100002'=>'数据传入，格式有误（手机号，邮箱）',
    '100003'=>'数据传入，数据无效（Token）',
    '100004'=>'未授权，非法访问',
];
$config =  [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases'=>[
        '@api'=>'@app',
    ],
    'controllerNamespace' => 'restapi\controllers',
    'modules' => [
//         'user' => [
//             'class' => 'api\modules\user\Module',
//         ],
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
                'from'=>['linuu90@163.com'=>'APIForDevelopLocalhost']
            ],
        ],
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
    'params' => $params,
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

