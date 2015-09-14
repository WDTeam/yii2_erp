<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=rds8fo0wqso6bdbj8818.mysql.rds.aliyuncs.com;dbname=sq_ejiajie_v2',
            'username' => 'sq_ejiajie',
            'password' => 'dudu1020',
            'charset' => 'utf8',
        ],
        'dbv1' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=rds8fo0wqso6bdbj8818.mysql.rds.aliyuncs.com;dbname=sq_ejiajie',
            'username' => 'sq_ejiajie',
            'password' => 'dudu1020',
            'charset' => 'utf8',
        ],
        'dbv2' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=rds8fo0wqso6bdbj8818.mysql.rds.aliyuncs.com;dbname=sq_ejiajie_v2',
            'username' => 'sq_ejiajie',
            'password' => 'dudu1020',
            'charset' => 'utf8',
        ]
    ]
];
