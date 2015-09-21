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
        // Url映射规则
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
        /**
         * 配置邮箱账号 
         *  modified by zhanghang 2015-09-21
         */
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'corp.1jiajie.com',
                'username' => 'service@corp.1jiajie.com',
                'password' => '123qweASDZXC',
                'port' => '25',
//                'encryption' => 'ssl',
        
            ],
        ],
        /**
         * Log 日志配置
         *  add by zhanghang 2015-09-21
         */
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
        /**
         * 配置 redis 
         *  add by zhanghang 2015-09-21
         * 
         * 使用方式： Yii::$app->redis->set('keyname','keyvalue');
         * 
         * 有部分函数使用时要注意，比如mget:
         * $tempkeyarray=explode(',',$tempkey);
         * $resultarray = Yii::$app->redis->executeCommand('mget',$tempkeyarray);
         * 
         * 修改php.ini，设置： 
         * default_socket_timeout = -1
         * 可以在index.php入口处加：
         *   <?php
         *      ini_set('default_socket_timeout', -1);
         */
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost', // 配置为 redis 服务器地址
            'port' => 6379,
            'database' => 0,
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