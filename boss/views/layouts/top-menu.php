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
        'url' => ['/']
    ],
    [
        'label' => Yii::t('app', 'Logout') . '(' . Yii::$app->user->identity->username . ')',
        'url' => ['/system/site/logout'],
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
        'label' => '<i class="fa fa-cog"></i> ' . Yii::t('app', '系统设置'),
        'url' => ['#'],
        'active' => false,
        //'visible' => Yii::$app->user->can('haha'),
        'items' => [
          [
              'label' => '<i class="fa fa-file-text"></i> ' . Yii::t('app', '设置系统授权项'),
              'url' => ['/system/auth'],
          ],
          [
              'label' => '<i class="fa fa-users"></i> ' . Yii::t('app', '设置系统用户角色'),
              'url' => ['/system/role'],
          ],
          [
              'label' => '<i class="fa fa-user"></i> ' . Yii::t('app', '设置系统用户权限'),
              'url' => ['/system/system-user'],
          ],
          [
              'label' => '<i class="fa fa-sign-out"></i> ' . Yii::t('app', '退出系统'),
              'url' => ['/system/site/logout'],
            ],
        ],
    ],
//     [
//         'label' => '<i class="fa fa-cog"></i> ' . Yii::t('app', '用户'),
//         'url' => ['#'],
//         'active' => false,
//         //'visible' => Yii::$app->user->can('haha'),
//         'items' => [
//             [
//                 'label' => '<i class="fa fa-user"></i> ' . Yii::t('app', '登出'),
//                 'url' => ['/system/site/logout'],
//             ],
//             [
//                 'label' => '<i class="fa fa-lock"></i> ' . Yii::t('app', '授权列表'),
//                 'url' => ['/rbac'],
//             ],
//             [
//                 'label' => '<i class="fa fa-lock"></i> ' . Yii::t('app', '创建角色'),
//                 'url' => ['/rbac/create'],
//             ],
//             [
//                 'label' => '<i class="fa fa-lock"></i> ' . Yii::t('app', '创建授权项'),
//                 'url' => ['/rbac/auto'],
//             ],
//             [
//                 'label' => '<i class="fa fa-lock"></i> ' . Yii::t('app', '分配授权项'),
//                 'url' => ['/rbac/assign'],
//             ],
//         ],
//     ],
//     [
//         'label' => '<i class="fa fa-cog"></i> ' . Yii::t('app', '吐槽'),
//         'url' => ['#'],
//         'active' => false,
//         //'visible' => Yii::$app->user->can('haha'),
//         'items' => [
//             [
//                 'label' => '<i class="fa fa-user"></i> ' . Yii::t('app', 'User'),
//                 'url' => ['/system/user'],
//             ],
//             [
//                 'label' => '<i class="fa fa-lock"></i> ' . Yii::t('app', 'Role'),
//                 'url' => ['/system/role'],
//             ],
//         ],
//     ],
];
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $menuItemsMain,
    'encodeLabels' => false,
]);

//NavBar::end();
