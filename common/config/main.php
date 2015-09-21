<?php

return [
    'name' => 'BOSS',
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => ['log', 'devicedetect'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '8P1C8K-jX8XahGh_4l_o3jxTxDIVLCIr',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],
        'cache' => [
             'class' => 'yii\caching\FileCache',
 //           'class'=>'yii\caching\DbCache',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '@app/messages',
                    //'sourceLanguage' => 'en',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
                /*'yii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'zh-CN',
                    'basePath' => '@app/messages'
                ],*/
            ],
        ],
        'formatter' => [
            'dateFormat' => 'yyyy-MM-dd',
            'datetimeFormat' => 'yyyy-MM-dd HH:mm:ss',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'CNY',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'enableStrictParsing' => true,
            'rules' => [
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.qq.com',
                'username' => 'lidenggao@1jiajie.com',
                'password' => 'ldg1234',
                'port' => '465',
                'encryption' => 'ssl',
        
            ],
        ],
        'log' => [
            'targets' => [
                'fileError' => [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                    'categories' => ['yii\*'],
                    'logFile' => '@app/runtime/logs/error.log',
                ],
                'fileWarning' => [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['warning'],
                    'categories' => ['yii\*'],
                    'logFile' => '@app/runtime/logs/warning.log',
                ],
                'fileInfo' => [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['trace', 'info'],
                    'categories' => ['yii\*'],
                    'logFile' => '@app/runtime/logs/info.log',
                ],
            ],
        ],
        'devicedetect' => [
            'class' => 'alexandernst\devicedetect\DeviceDetect'
        ],
    ],
    'modules' => [
        'datecontrol' =>  [
            'class' => 'kartik\datecontrol\Module',
 
            // format settings for displaying each date attribute
            'displaySettings' => [
                'date' => 'd-m-Y',
                'time' => 'H:i:s A',
                'datetime' => 'd-m-Y H:i:s A',
            ],
 
            // format settings for saving each date attribute
            'saveSettings' => [
                'date' => 'Y-m-d', 
                'time' => 'H:i:s',
                'datetime' => 'Y-m-d H:i:s',
            ],
             // automatically use kartik\widgets for each of the above formats
            'autoWidget' => true,
 
        ]
    ],
];