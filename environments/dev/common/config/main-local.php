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
            'dsn' => 'mysql:host=rdsh52vh252q033a4ci5.mysql.rds.aliyuncs.com;dbname=dev-boss-db',
            'username' => 'dev_boss_db_dbo',
            'password' => 'devboss',
            'tablePrefix' => 'ejj_',
            'charset' => 'utf8',
        ],
        'cache' => [
            //'class' => 'yii\caching\FileCache',
//            'class'=>'yii\caching\DbCache',
            'class' => 'yii\redis\Cache',
            ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '101.200.179.70', // 配置为 dev环境 redis 服务器地址 test环境 101.200.200.74 ，prod环境 待定
            'port' => 6379,
            'database' => 0,
            ],
    ]
];
