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
 
    ],
];
