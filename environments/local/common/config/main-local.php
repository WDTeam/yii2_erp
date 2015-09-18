<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=local-boss-db',
            'username' => 'local_boss_db_dbo',
            'password' => 'localboss',
            'tablePrefix' => 'ejj_',
            'charset' => 'utf8',
        ]
      
    ]
];
