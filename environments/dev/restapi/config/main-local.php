<?php

$config = [
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
                'from'=>['linuu90@163.com'=>'APIForDevelop']
            ],
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
