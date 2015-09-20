<?php
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

/*NavBar::begin([
    'brandLabel' => 'My Company',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);*/
$menuItems = [
    [
        'label' => Yii::t('app', 'Home'),
        'url' => ['/site/index']
    ],
    [
        'label' => Yii::t('app', 'Logout') . '(' . Yii::$app->user->identity->username . ')',
        'url' => ['/site/logout'],
        'linkOptions' => ['data-method' => 'post']
    ]
];
//echo Nav::widget([
//    'options' => ['class' => 'navbar-nav navbar-right'],
//    'items' => $menuItems,
//]);

$menuItemsMain = [
    [
        'label' => '<i class="fa fa-cog"></i> ' . Yii::t('app', 'InterviewManagement'),
        'url' => ['#'],
        'active' => false,
        'items' => [
            [
                'label' => '<i class="fa fa-user"></i> ' . Yii::t('app', 'Catalog'),
                'url' => ['/interview/#1'],
            ],
            [
                'label' => '<i class="fa fa-user-md"></i> ' . Yii::t('app', 'Class'),
                'url' => ['/interview/#2'],
            ],
            [
                'label' => '<i class="fa fa-user-md"></i> ' . Yii::t('app', 'Comment'),
                'url' => ['/interview/#3'],
            ],
            [
                'label' => '<i class="fa fa-user-md"></i> ' . Yii::t('app', 'Tag'),
                'url' => ['/interview/#4'],
            ],
        ],
        'visible' => Yii::$app->user->can('readPost'),
    ],
    [
        'label' => '<i class="fa fa-cog"></i> ' . Yii::t('app', '配置'),
        'url' => ['#'],
        'active' => false,
        //'visible' => Yii::$app->user->can('haha'),
        'items' => [
            [
                'label' => '<i class="fa fa-user"></i> ' . Yii::t('app', 'User'),
                'url' => ['/user'],
            ],
            [
                'label' => '<i class="fa fa-lock"></i> ' . Yii::t('app', 'Role'),
                'url' => ['/role'],
            ],
        ],
    ],
    [
        'label' => '<i class="fa fa-cog"></i> ' . Yii::t('app', '用户'),
        'url' => ['#'],
        'active' => false,
        //'visible' => Yii::$app->user->can('haha'),
        'items' => [
            [
                'label' => '<i class="fa fa-user"></i> ' . Yii::t('app', 'User'),
                'url' => ['/user'],
            ],
            [
                'label' => '<i class="fa fa-lock"></i> ' . Yii::t('app', 'Role'),
                'url' => ['/role'],
            ],
        ],
    ],
    [
        'label' => '<i class="fa fa-cog"></i> ' . Yii::t('app', '吐槽'),
        'url' => ['#'],
        'active' => false,
        //'visible' => Yii::$app->user->can('haha'),
        'items' => [
            [
                'label' => '<i class="fa fa-user"></i> ' . Yii::t('app', 'User'),
                'url' => ['/user'],
            ],
            [
                'label' => '<i class="fa fa-lock"></i> ' . Yii::t('app', 'Role'),
                'url' => ['/role'],
            ],
        ],
    ],
];
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $menuItemsMain,
    'encodeLabels' => false,
]);

//NavBar::end();

