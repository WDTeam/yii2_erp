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
            'dsn' => 'mysql:host=localhost;dbname=local-boss-db',
            'username' => 'root',
            'password' => '',
            'tablePrefix' => 'et_',
            'charset' => 'utf8',
            ],
       ],
];