<?php
use common\widgets\Menu;

$ctrl = Yii::$app->controller;

echo Menu::widget(
    [
        'options' => [
            'class' => 'sidebar-menu'
        ],
        'items' => [
            /* 面试管理 */
                        [
                'label' => Yii::t('app', 'InterviewManagement'),
                'url' => ['#'],
                'icon' => 'fa-dashboard',
                'options' => [
                    'class' => 'treeview',
                ],
                'visible' => Yii::$app->user->can('readPost'),
                'items' => [
                    [
                        'label' => Yii::t('app', 'Interview'),
                        'url' => ['/interview/index'],
                        'icon' => 'fa fa-user',
                    ],
//                    [
//                        'label' => Yii::t('app', 'Agreement'),
//                        'url' => ['/signed/index'],
//                        'icon' => 'fa fa-lock',
//                    ],
                ],
            ],
             /* 服务管理 */
                        [
                'label' => Yii::t('app', 'CategoryManagement'),
                'url' => ['category/index'],
                'icon' => 'fa-dashboard',
                'options' => [
//                     'class' => 'treeview',
                ],
                'active'=>isset($ctrl->is_category_manage),
                'visible' => Yii::$app->user->can('readPost'),
            ],
            /* 控制面板 */
            [
                'label' => Yii::t('app', 'Dashboard'),
                'url' => Yii::$app->homeUrl,
                'icon' => 'fa-dashboard',
                'active' => Yii::$app->request->url === Yii::$app->homeUrl
            ],
            [
                'label' => Yii::t('app', 'Settings'),
                'url' => ['#'],
                'icon' => 'fa fa-spinner',
                'options' => [
                    'class' => 'treeview',
                ],
                'visible' => Yii::$app->user->can('readPost'),
                'items' => [
                    [
                        'label' => Yii::t('app', 'Basic'),
                        'url' => ['#'],
                        'icon' => 'fa fa-user',
                    ],
                    [
                        'label' => Yii::t('app', 'Advanced'),
                        'url' => ['#'],
                        'icon' => 'fa fa-lock',
                    ],
                ],
            ],
            [
                'label' => Yii::t('app', 'System'),
                'url' => ['#'],
                'icon' => 'fa fa-cog',
                'options' => [
                    'class' => 'treeview',
                ],
                'items' => [
                    [
                        'label' => Yii::t('app', 'User'),
                        'url' => ['/user/index'],
                        'icon' => 'fa fa-user',
                        //'visible' => (Yii::$app->user->identity->username == 'admin'),
                    ],
                    [
                        'label' => Yii::t('app', 'Role'),
                        'url' => ['/role/index'],
                        'icon' => 'fa fa-lock',
                    ],
                ],
            ],
        ]
    ]
);