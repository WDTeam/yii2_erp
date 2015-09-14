<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.3.149;dbname=train',
            'username' => 'train',
            'password' => 'Ejiajie1',
            'tablePrefix' => 'et_',
            'charset' => 'utf8',
        ],
        'dbv1' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=rdsh52vh252q033a4ci5.mysql.rds.aliyuncs.com;dbname=sq_ejiajie',
            'username' => 'sq_ejiajie',
            'password' => 'test_sq_ejiajie',
            'charset' => 'utf8',
        ],
        'dbv2' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=rdsh52vh252q033a4ci5.mysql.rds.aliyuncs.com;dbname=sq_ejiajie_v2',
            'username' => 'sq_ejiajie',
            'password' => 'test_sq_ejiajie',
            'charset' => 'utf8',
        ]
    ]
];