<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=rdsh52vh252q033a4ci5.mysql.rds.aliyuncs.com;dbname=dev-boss-db',
            'username' => 'dev_boss_db_dbo',
            'password' => 'devboss',
            'tablePrefix' => 'ejj_',
            'charset' => 'utf8',
        ]
     
    ]
];
