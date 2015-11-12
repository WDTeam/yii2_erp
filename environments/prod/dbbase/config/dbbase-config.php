<?php

return [
    'name' => 'BOSS',
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => ['log', 'devicedetect', 'ivr', 'core\components\EventBind'],
    'components' => [

        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=local-boss-db',
            'username' => 'root',
            'password' => '',
            'tablePrefix' => 'ejj_',
            'charset' => 'utf8',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '101.200.179.70', // 配置为 dev环境 redis 服务器地址 test环境 101.200.200.74 ，prod环境 待定
            //            'hostname' => '127.0.0.1', // 配置为 dev环境 redis 服务器地址 test环境 101.200.200.74 ，prod环境 待定
            'port' => 6379,
            'database' => 0,
        ],
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://admin:Ejiajie2015@101.200.179.70:27017/boss_prod',
        ],
        /**
         * 极光推送,默认为开发环境配置
         */
        'jpush' => [
            'class' => 'dbbase\components\JPush',
            'app_key' => '507d4a12d19ebbab7205f6bb',
            'master_secret' => '30d1653625e797b7f80b56bb'
        ],
        /**
         * 发短信配置
         */
        'sms' => [
            'class' => 'dbbase\components\Sms',
            'userId' => 'J02356',
            'password' => '556201',
        ],
        /**
         * IVR
         */
        'ivr' => [
            'class' => 'dbbase\components\Ivr',
            'app_id' => '5000058',
            'token' => '57b62a3462b52a1413a4e1934a60d983',
            'redirect_uri' => 'system/ivr/callback'
        ],
        /**
         * 七牛
         */
        'imageHelper'=>[
            'class'=>'core\components\ImageHelper',
            'accessKey' => 'kaMuZPkS_f_fxcfsDKET0rTst-pW6Ci7GMlakffw',
            'secretKey' => 'HEMGszOQBpQEC_GMqFqT_mwQW0ypQoE0Y3uhCllq',
            'domain' => '7b1f97.com1.z0.glb.clouddn.com',
            'bucket' => 'bjzhichangmusic'
        ],
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

        /**
         * 配置邮箱账号
         *  modified by zhanghang 2015-09-21
         */
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@dbbase/mail',
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
         * Log 日志配置
         *  add by zhanghang 2015-09-21
         */
        'log' => [
            'targets' => [
                'email' => [
                    'class' => 'yii\log\EmailTarget',
                    'mailer' => 'mailer',
                    'levels' => ['error'],
                    'categories' => ['event\*'],
                    'message' => [
                        'from' => 'service@corp.1jiajie.com',
                        'to' => ['lidenggao@1jiajie.com'],
                        'subject' => '事件绑定处理错误日志',
                    ],
                ],
            ],
        ],
        'devicedetect' => [
            'class' => 'alexandernst\devicedetect\DeviceDetect'
        ],

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
        'datecontrol' => [
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
        'dynagrid' => [
            'class' => '\kartik\dynagrid\Module',
            // other module settings
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module',
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
            'imageAllowExtensions' => ['jpg', 'png', 'gif']
        ],
    ],
    'params' => [
        'adminEmail' => 'admin@1jiajie.com',
        'supportEmail' => 'support@1jiajie.com',
        'user.passwordResetTokenExpire' => 3600,
        /**
         * 配置财务数字显示
         *  add by zhanghang 2015-09-22
         */
        'maskMoneyOptions' => [
            'prefix' => '¥ ', // ¥ 在HTML，“¥”的命名实体是“&yen;”，x字符代码是“&#165;”和“&#xA5;”;
            'suffix' => '',
            'affixesStay' => true,
            'thousands' => ',',
            'decimal' => '.',
            'precision' => 2,
            'allowZero' => false,
            'allowNegative' => false,
        ],
        "order_pop" => [
            'api_url' => 'http://pop.1jiajie.com/'
        ],
        'order' => [
            'MANUAL_ASSIGN_lONG_TIME' => 900,
            'ORDER_BOOKED_WORKER_ASSIGN_TIME' => 900,
            'ORDER_FULL_TIME_WORKER_SYS_ASSIGN_TIME' => 300,
            'ORDER_PART_TIME_WORKER_SYS_ASSIGN_TIME' => 900,
            'USE_ORDER_FLOW_SERVICE_ITEMS'=>[
                '家庭保洁'
            ]
        ],
        'uploadpath' => true, //true上传到七牛 false 上传的本地
        'worker_base_salary' => 3000,//阿姨的底薪
        'unit_order_money_nonself_fulltime' => 50,//小家政全时段阿姨补贴的每单的金额
        'order_count_per_week' => 12,//小家政全时段阿姨的底薪策略是保单，每周12单
    ],
];
