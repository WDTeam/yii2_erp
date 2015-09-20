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
            [
                'label' => '门店管理',
                'url' => ['#'],
                'icon' => 'fa fa-flag',
                'options' => [
                    'class' => 'treeview active',
                ],
                'items' => [
                    [
                        'label' => '查看所有门店',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                        //'visible' => (Yii::$app->user->identity->username == 'admin'),
                    ],
                    [
                        'label' => '添加新门店',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                ],
            ],
            [
                'label' => '阿姨管理',
                'url' => ['#'],
                'icon' => 'fa fa-female',
                'options' => [
                    'class' => 'treeview active',
                ],
                'items' => [
                    [
                        'label' => '查看所有阿姨',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '录入新阿姨',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '管理黑名单阿姨',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                ],
            ],
            [
                'label' => '顾客管理',
                'url' => ['#'],
                'icon' => 'fa fa-user',
                'options' => [
                    'class' => 'treeview active',
                ],
                'items' => [
                    [
                        'label' => '查看所有顾客',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '管理黑名单顾客',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                ],
            ],
            [
                'label' => '订单管理',
                'url' => ['#'],
                'icon' => 'fa fa-tag',
                'options' => [
                    'class' => 'treeview active',
                ],
                'items' => [
                    [
                        'label' => '查看所有订单',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '创建新订单',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '操作订单',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                ],
            ],
            [
                'label' => '财务管理',
                'url' => ['#'],
                'icon' => 'fa fa-credit-card',
                'options' => [
                    'class' => 'treeview active',
                ],
                'items' => [
                    [
                        'label' => '对账管理',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '退款管理',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '赔偿管理',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '结算管理',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '报表管理',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '发票管理',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '支付渠道管理',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                ],
            ],
            [
                'label' => '运营管理',
                'url' => ['#'],
                'icon' => 'fa fa-recycle',
                'options' => [
                    'class' => 'treeview active',
                ],
                'items' => [
                    [
                        'label' => '用户运营',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '阿姨运营',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '企业运营',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => 'CMS管理',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '通知管理',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '促销管理',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                ],
            ],
            [
                'label' => '区域服务管理',
                'url' => ['#'],
                'icon' => 'fa fa-globe',
                'options' => [
                    'class' => 'treeview active',
                ],
                'items' => [
                    [
                        'label' => '城市管理',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '商圈管理',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '服务管理',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                ],
            ],
            [
                'label' => '供应商管理',
                'url' => ['#'],
                'icon' => 'fa fa-ambulance',
                'options' => [
                    'class' => 'treeview active',
                ],
                'items' => [
                    [
                        'label' => '查看所有供应商',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '添加新供应商',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '服务供应商',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '商品供应商',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                ],
            ],
            [
                'label' => 'POP管理',
                'url' => ['#'],
                'icon' => 'fa fa-random',
                'options' => [
                    'class' => 'treeview active',
                ],
                'items' => [
                    [
                        'label' => '查看所有渠道',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '添加新渠道',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                ],
            ],
            [
                'label' => '小家政管理',
                'url' => ['#'],
                'icon' => 'fa fa-slideshare',
                'options' => [
                    'class' => 'treeview active',
                ],
                'items' => [
                    [
                        'label' => '查看所有家政公司',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '添加新家政公司',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                ],
            ],
        ]
    ]
);