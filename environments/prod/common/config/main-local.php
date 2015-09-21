<?php

/*
*  这个文件只能由运维的同事来配置，开发人员绝对不能修改
*/
error_reporting(0);
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=;dbname=',
            'username' => '',
            'password' => '',
            'charset' => 'utf8',
        ],

    ]
];
