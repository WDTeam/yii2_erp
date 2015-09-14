<?php

$config = [
    'components' => [
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' =>false,//这句一定有，false发送邮件，true只是生成邮件在runtime文件夹下，不发邮件
            'viewPath' => 'common/mail',
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
                'from'=>['linuu90@163.com'=>'APIForTest']
            ],
        ],
    ],
];
return $config;
