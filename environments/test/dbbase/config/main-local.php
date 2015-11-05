<?php

/*
*  绝对绝对绝对不要修改这个文件！！！！！！（郭红波）
*  绝对绝对绝对不要修改这个文件！！！！！！（郭红波）
*  绝对绝对绝对不要修改这个文件！！！！！！（郭红波）
*/
error_reporting(E_ALL);
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=rdsl1g8oe3xdc1ul6l3i.mysql.rds.aliyuncs.com;dbname=test_boss_db',
            'username' => 'test_boss_db_dbo',
            'password' => 'test_boss',
            'tablePrefix' => 'ejj_',
            'charset' => 'utf8',

        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'test.boss.1jiajie.com', // 配置为 dev环境 redis 服务器地址 test环境 101.200.200.74 ，prod环境 待定
            //            'hostname' => '127.0.0.1', // 配置为 dev环境 redis 服务器地址 test环境 101.200.200.74 ，prod环境 待定
            'port' => 6379,
            'database' => 0,
        ],
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://101.200.179.70:27017/boss_dev_log',
        ],
        /**
         * 极光推送,默认为开发环境配置
        * //正式
        $app_key='507d4a12d19ebbab7205f6bb';
        $master_secret = '30d1653625e797b7f80b56bb';
        // 测试
        $app_key='6b79c45db3ed3aa1706778f9';
        $master_secret = '7bcba44668a3ff6469fb57a5';
        //dev
        $app_key='3037ca7c859cca4c996f7144';
        $master_secret = 'a064811d7e4596c32d0e6884';
        */
        'jpush'=>[
            'class'=>'dbbase\components\JPush',
            'app_key'=>'6b79c45db3ed3aa1706778f9',
            'master_secret'=>'7bcba44668a3ff6469fb57a5'
        ],
        /**
         * 发短信配置
        */
        'sms'=>[
            'class'=>'dbbase\components\Sms',
            'userId'=>'J02356',
            'password'=>'556201',
        ],
        /**
         * IVR
        */
        'ivr'=>[
            'class'=>'dbbase\components\Ivr',
            'app_id'=>'5000040',
            'token'=>'8578b07ba71ff7dfd6ddeca95d69828c',
            'redirect_uri'=>'system/ivr/callback'
        ],
    ],
];
