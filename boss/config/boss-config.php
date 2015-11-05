<?php
$params = [
'uploadpath' =>true, //true上传到七牛 false 上传的本地
'worker_base_salary'=>3000,//阿姨的底薪
'unit_order_money_nonself_fulltime' =>50,//小家政全时段阿姨补贴的每单的金额
'order_count_per_week'=>12,//小家政全时段阿姨的底薪策略是保单，每周12单
//    'order'=>[
//        'MANUAL_ASSIGN_lONG_TIME'=>900,
//        'ORDER_BOOKED_WORKER_ASSIGN_TIME'=>900,
//        'ORDER_FULL_TIME_WORKER_SYS_ASSIGN_TIME'=>300,
//        'ORDER_PART_TIME_WORKER_SYS_ASSIGN_TIME'=>900,
//    ]
];

$config =  [
    'id' => 'app-boss',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'boss\controllers',
    'bootstrap' => ['log'],
    'modules' => [
//        'blog' => [
//            'class' => 'funson86\blog\Module',
//            'controllerNamespace' => 'funson86\blog\controllers\boss'
//        ],
        'dynagrid'=> [
            'class'=>'\kartik\dynagrid\Module',
        ],
        'gridview' => [
            'class' => 'kartik\grid\Module',
        ],
    ],
    'components' => [
        'user' => [
            'identityClass' => 'core\models\system\SystemUser',
            'enableAutoLogin' => true,
        ],
        
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'enableStrictParsing' => true,
            'rules' => [
                ''=>'system/site/index',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'system/site/error',
        ],
        'areacascade' => [
            'class' => 'boss\components\AreaCascade'
        ],
    ],
    'params' => $params,
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii']['class'] = 'yii\gii\Module';
    //Add this into backend/config/main-local.php
    $config['modules']['gii']['generators'] = [
        'kartikgii-crud' => ['class' => 'warrence\kartikgii\crud\Generator'],
    ];
}
function dump($_data){
    echo '<pre>';
    print_r($_data);
    echo '</pre>';
}
return $config;