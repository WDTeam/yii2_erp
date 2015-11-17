<?php
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use boss\components\RbacHelper;

/*
 * NavBar::begin([
 * 'brandLabel' => 'My Company',
 * 'brandUrl' => Yii::$app->homeUrl,
 * 'options' => [
 * 'class' => 'navbar-inverse navbar-fixed-top',
 * ],
 * ]);
 */
 
$menuItemsMain = RbacHelper::topMenu([
    [
        'label' => '<i class="fa fa-cog"></i> ' . Yii::t('app', '系统设置'),
        'url' => [
            '#'
        ],
        'active' => false,
        'items' => [
            [
                'label' => '<i class="fa fa-file-text"></i> ' . Yii::t('app', '管理系统授权项'),
                'url' => [
                    'system/auth/index'
                ]
            ],
            [
                'label' => '<i class="fa fa-users"></i> ' . Yii::t('app', '管理系统角色'),
                'url' => [
                    'system/role/index'
                ]
            ],
            [
                'label' => '<i class="fa fa-user"></i> ' . Yii::t('app', '管理系统用户'),
                'url' => [
                    'system/system-user/index'
                ]
            ],
        ]
    ]
]);
$menuItemsMain[0]['items'][] = [
    'label' => '<i class="fa fa-user"></i> ' . Yii::t('app', '更新个人资料'),
    'url' => [
        'system/system-user/update-profile'
    ]
];
$menuItemsMain[0]['items'][] = [
    'label' => '<i class="fa fa-sign-out"></i> ' . Yii::t('app', '退出系统'),
    'url' => [
        'system/site/logout'
    ]
];
echo Nav::widget([
    'options' => [
        'class' => 'navbar-nav navbar-right'
    ],
    'items' => $menuItemsMain,
    'encodeLabels' => false
]);

//NavBar::end();
