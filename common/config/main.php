<?php

return [
    'name' => 'BOSS',
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => ['log', 'devicedetect', 'ivr'],
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
            //'class' => 'yii\caching\FileCache',
//            'class'=>'yii\caching\DbCache',
            'class' => 'yii\redis\Cache',
        ],
        'file_cache' => [
            'class' => 'yii\caching\FileCache',
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
            'hostname' => '101.200.179.70', // 配置为 dev环境 redis 服务器地址 test环境 101.200.200.74 ，prod环境 待定
//            'hostname' => '127.0.0.1', // 配置为 dev环境 redis 服务器地址 test环境 101.200.200.74 ，prod环境 待定
            'port' => 6379,
            'database' => 0,
        ],
        /**
         * 配置 mongodb
         * 使用参考：http://www.yiiframework.com/doc-2.0/ext-mongodb-index.html
         */
        //'mongodb' => [
            //'class' => '\yii\mongodb\Connection',
            //'dsn' => 'mongodb://developer:password@localhost:27017/mydatabase',
        //],
        
        /**
         * 配置控制台命令
         * 
         * 使用方法：
         * $output = '';
         * Yii::$app->consoleRunner->run('controller/action param1 param2 ...', $output);
         * echo $output; //prints the command output
         */
        'consoleRunner' => [
            'class' => 'toriphes\console\Runner'
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
        ],
        /**
         * 配置动态grid表格控件
         *  add by zhanghang 2015-09-23
         * 
         * yii2-dynagrid模块是一个很好的互补的kartik-v / yii2-grid模块,加强个性化特性。涡轮指控你的网格视图,它为每个用户动态和个性化。它允许用户设置和保存自己的网格配置。这个模块提供的主要功能有:
         * 个性化设置,并保存电网在运行时页面大小。你可以设置最小和最大允许页面大小。
         * 个性化设置,并保存网格数据过滤器在运行时。用户可以定义并保存他/她自己的个性化的网格数据过滤器。
         * 个性化设置,并保存在运行时网格列排序。用户可以定义并保存他/她自己的个性化网格列排序。
         * 通过拖拽个性化网格列显示。重新排序网格列和列设置所需的可见性,并允许用户保存该设置。控制哪些列可以通过预定义的用户重新排序的列设置。预先确定你想要哪一列将总是默认固定到左边或者右边。
         * 网格外观和个性化设置网格的主题。这将提供高级定制的网格布局。它允许用户几乎风格电网无论如何他们想要的,基于你如何定义主题和扩展你的用户。因为扩展使用yii2-grid扩展,它提供了所有的样式选项yii2-grid扩展提供了包括各种网格列的增强,引导板和其他网格样式。这将允许您轻松地为用户设置的主题在许多方面。你有能力设置多个主题模块配置,并允许用户选择其中之一。默认的扩展包括一些预定义的主题开始。
         * 允许你保存动态网格配置特定于每个用户或全球层面。实现了一个 DynaGridStore对象来管理dynagrid个人化操作独立的存储。下列存储选项可用来存储个性化的网格配置:
         * 会话存储(默认)
         * Cookie存储
         * 数据库存储
         * 扩展自动验证基于存储和加载已保存的配置设置。
         * 
         * 查看完整的演示效果：http://demos.krajee.com/dynagrid-demo
         * 使用参考：http://demos.krajee.com/dynagrid
         */
        'dynagrid'=> [
                     'class'=>'\kartik\dynagrid\Module',
                     // other module settings
        ],
        'gridview'=> [
             'class'=>'\kartik\grid\Module',
             // other module settings
         ],
        /**
         * 配置 redactor
         * 
         * 使用参考：https://github.com/yiidoc/yii2-redactor
         * 方法1：
         * <?= \yii\redactor\widgets\Redactor::widget([
         *    'model' => $model,
         *    'attribute' => 'body'
         * ]) ?>
         * 
         * 方法2：
         * <?= $form->field($model, 'body')->widget(\yii\redactor\widgets\Redactor::className(), [
         *     'clientOptions' => [
         *         'imageManagerJson' => ['/redactor/upload/image-json'],
         *         'imageUpload' => ['/redactor/upload/image'],
         *         'fileUpload' => ['/redactor/upload/file'],
         *         'lang' => 'zh_cn',
         *         'plugins' => ['clips', 'fontcolor','imagemanager']
         *     ]
         * ])?>
         * 
         */
        'redactor' => [
            'class' => 'yii\redactor\RedactorModule',
            'uploadDir' => '@webroot/path/to/uploadfolder',
            'uploadUrl' => '@web/path/to/uploadfolder',
            'imageAllowExtensions'=>['jpg','png','gif']
        ],
    ],
];
