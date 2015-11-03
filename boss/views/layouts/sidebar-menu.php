<style type="text/css">
    .skin-blue .sidebar > .sidebar-menu > li:hover { color: #f6a202 !important;}
    ul.sidebar-menu ul.treeview-menu li.active a.active {color: #f6a202;font-weight: bold;}
    .sidebar .sidebar-menu .treeview-menu > li > a:hover {color: #f6a202;font-weight: bold;}
    .skin-blue .sidebar > .sidebar-menu > li > a:hover, .skin-blue .sidebar > .sidebar-menu > li.active > a {color: #f6a202;font-weight: bold;}
</style>
<?php
use boss\widgets\Menu;
use core\models\finance\FinanceSettleApplySearch;
use core\models\finance\FinanceShopSettleApplySearch;

$ctrl = Yii::$app->controller;

echo Menu::widget(
    [
        'options' => [
            'class' => 'sidebar-menu'
        ],
        'items' => [
            [
                'label' => '家政公司管理',
                'url' => ['#'],
                'icon' => 'fa fa-slideshare',
                'options' => [
                    'class' => 'treeview rootTree',
                ],
                'visible' => (Yii::$app->user->can('sidebar-housekeep')),
                'items' => [
                    [
                        'label' => '查看所有家政公司',
                        'url' => ['shopmanager/shop-manager/index'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '添加新家政',
                        'url' => ['shopmanager/shop-manager/create'],
                        'icon' => 'fa fa-angle-right',
                    ],
                ],
            ],
            [
                'label' => '门店管理',
                'url' => ['#'],
                'icon' => 'fa fa-flag',
                'visible' => (Yii::$app->user->can('sidebar-shop')),
                'options' => [
                    'class' => 'treeview rootTree',
                ],
                'items' => [
                    [
                        'label' => '查看所有门店',
                        'url' => ['shop/shop/index'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '添加新门店',
                        'url' => ['shop/shop/create'],
                        'icon' => 'fa fa-angle-right',
                    ],
                ],
            ],
            [
                'label' => '阿姨管理',
                'url' => ['#'],
                'icon' => 'fa fa-female',
                'visible' => (Yii::$app->user->can('sidebar-worker')),
                'options' => [
                    'class' => 'treeview rootTree',
                ],
                'items' => [
                    [
                        'label' => '查看所有阿姨',
                        'url' => ['/worker/worker'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '录入新阿姨',
                        'url' => ['/worker/worker/create'],
                        'icon' => 'fa fa-angle-right',
                    ],
//                     [
//                         'label' => '阿姨黑名单(待定)',
//                         'url' => ['/worker/index?WorkerSearch[worker_is_blacklist]=1'],
//                         'icon' => 'fa fa-angle-right',
//                     ],
                ],
            ],
            [
                'label' => '客户管理',
                'url' => ['#'],
                'icon' => 'fa fa-user',
                'visible' => (Yii::$app->user->can('sidebar-customer')),
                'options' => [
                    'class' => 'treeview rootTree',
                ],
                'items' => [
                    [
                        'label' => '查看所有客户',
                        'url' => ['/customer/customer/index?CustomerSearch[is_del]=0'],
                        'icon' => 'fa fa-angle-right',
                    ],
					[
					'label' => '评价列表',
					'url' => ['/customer/customer-comment'],
					'icon' => 'fa fa-angle-right',
					],
					[
					'label' => '评价标签管理',
					'url' => ['/customer/customer-comment-tag'],
					'icon' => 'fa fa-angle-right',
					],
                ],
            ],
            [
                'label' => '订单管理',
                'url' => ['#'],
                'icon' => 'fa fa-tag',
                'visible' => (Yii::$app->user->can('sidebar-order')),
                'options' => [
                    'class' => 'treeview rootTree',
                ],
                'items' => [
                    [
                        'label' => '查看所有订单',
                        'url' => ['/order/order'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '创建新订单',
                        'url' => ['/order/order/create'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '人工派单',
                        'url' => ['/order/order/assign'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '智能派单',
                        'url' => ['/order/auto-assign'],
                        'icon' => 'fa fa-angle-right',
                    ],
                	[
                		'label' => '订单投诉',
                		'url' => ['/order/order-complaint'],
                		'icon' => 'fa fa-angle-right',
                	],
                	[
                		'label' => '订单响应',
                		'url' => ['/order/order-response'],
                		'icon' => 'fa fa-angle-right',
                	],

                ],
            ],
            [
                'label' => '交易管理',
                'url' => ['#'],
                'icon' => 'fa fa-tag',
                'visible' => (Yii::$app->user->can('sidebar-payment')),
                'options' => [
                    'class' => 'treeview rootTree',
                ],
                'items' => [
                    [
                        'label' => '支付记录查询',
                        'url' => ['payment/payment/index'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '交易记录查询',
                        'url' => ['payment/payment-customer-trans-record/index'],
                        'icon' => 'fa fa-angle-right',
                    ],
                ],
            ],
            [
                'label' => '运营管理',
                'url' => ['#'],
                'icon' => 'fa fa-recycle',
                'visible' => (Yii::$app->user->can('sidebar-operation')),
                'options' => [
                    'class' => 'treeview rootTree',
                ],
                'items' => [
//                    [
//                        'label' => '用户运营(0%)',
//                        'url' => ['/operation-category'],
//                        'icon' => 'fa fa-angle-right',
//                    ],
//                    [
//                        'label' => '阿姨运营(0%)',
//                        'url' => ['#'],
//                        'icon' => 'fa fa-angle-right',
//                    ],
//                    [
//                        'label' => '企业运营(0%)',
//                        'url' => ['#'],
//                        'icon' => 'fa fa-angle-right',
//                    ],
                    [
                        'label' => '服务管理',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                        'options' => [
                            'class' => 'treeview ',
                        ],
                        'items' => [
                            [
                                'label' => '服务项目和类型管理',
                                'url' => ['/operation/operation-category/'],
                                'icon' => 'fa fa-angle-right',
                            ],
                            [
                                'label' => '城市和商圈管理',
                                'url' => ['/operation/operation-city'],
                                'icon' => 'fa fa-angle-right',
                            ],
                            [
                                'label' => '已开通城市管理',
                                'url' => ['/operation/operation-city/opencity'],
                                'icon' => 'fa fa-angle-right',

                            ],
                            [
                                'label' => '精品保洁管理',
                                'url' => ['/operation/operation-selected-service'],
                                'icon' => 'fa fa-angle-right',
                            ],
                        ]
                    ],
//                    [
//                        'label' => '上线城市(80%)',
//                        'url' => ['/operation-city/release'],
//                        'icon' => 'fa fa-angle-right',
//                        'visible' => (Yii::$app->user->identity->username == 'admin'),
//                    ],
                    [
                        'label' => 'CMS管理',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                        'options' => [
                            'class' => 'treeview ',
                        ],
                        'items' => [
                            [
                                'label' => '应用平台管理',
                                'url' => ['/operation/operation-platform'],
                                'icon' => 'fa fa-angle-right',
                            ],
                            //                            [
                                //                                'label' => '系统版本管理',
                                //                                'url' => ['/operation-platform-version'],
                                //                                'icon' => 'fa fa-angle-right',
                                //                            ],
                            [
                                'label' => '广告位置管理',
                                'url' => ['/operation/operation-advert-position'],
                                'icon' => 'fa fa-angle-right',
                            ],
                            [
                                'label' => '广告内容管理',
                                'url' => ['/operation/operation-advert-content'],
                                'icon' => 'fa fa-angle-right',

                            ],
                            [
                                'label' => '已发布广告管理',
                                'url' => ['/operation/operation-advert-release'],
                                'icon' => 'fa fa-angle-right',

                            ],
                        ]
                    ],
//                    [
//                        'label' => '通知管理(0%)',
//                        'url' => ['#'],
//                        'icon' => 'fa fa-angle-right',
//
//                    ],
					[
                        'label' => '优惠券管理',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                        'options' => [
                            'class' => 'treeview ',
                        ],
                        'items' => [
                            [
                                'label' => '优惠券列表',
                                'url' => ['operation/coupon/coupon/index'],
                                'icon' => 'fa fa-angle-right',
                            ],
                            [
                                'label' => '添加新优惠券',
                                'url' => ['operation/coupon/coupon/create'],
                                'icon' => 'fa fa-angle-right',
                            ],
                        
                        ]
                    ],

//                     [

//                        'label' => '商圈管理(40%)',
//                        'url' => ['/operation-shop-district'],
//                        'icon' => 'fa fa-angle-right',

//                    ],

//                    [
//                        'label' => '服务管理(80%)',
//                        'url' => ['/operation-goods'],
//                        'icon' => 'fa fa-angle-right',
//                    ],

                    [
                        'label' => '启动页管理',
                        'url' => ['/operation/operation-boot-page'],
                        'icon' => 'fa fa-angle-right',

                    ],
                    /**[
                     'label' => '引导页管理(0%)',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '话术管理(0%)',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],*/
                    [
                        'label' => '阿姨任务管理',
                        'url' => ['/operation/worker-task/index'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '服务卡管理',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                        'options' => [
                            'class' => 'treeview',
                        ],
                        'items' => [
                            [
                                'label' => '服务卡信息管理',
                                'url' => ['operation/operation-service-card-info/index'],
                                'icon' => 'fa fa-angle-right',
                            ],
                            [
                                'label' => '服务卡销售记录',
                                'url' => ['operation/operation-service-card-sell-record/index'],
                                'icon' => 'fa fa-angle-right',
                            ],
                            [
                                'label' => '服务卡客户关系',
                                'url' => ['operation/operation-service-card-with-customer/index'],
                                'icon' => 'fa fa-angle-right',
                            ],
                            [
                                'label' => '服务卡消费记录',
                                'url' => ['operation/operation-service-card-consume-record/index'],
                                'icon' => 'fa fa-angle-right',
                            ],

                        ],
                    ],
                ],
            ],
            [
                'label' => '财务管理',
                'url' => ['#'],
                'icon' => 'fa fa-credit-card',
                'visible' => (Yii::$app->user->can('sidebar-finance')),
                'options' => [
                    'class' => 'treeview rootTree',
                ],
                'items' => [
                    [
                        'label' => '对账管理',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                        'options' => [
                            'class' => 'treeview ',
                        ],
                        'items' => [
                            [
                                'label' => '渠道管理',
                                'url' => ['/finance/finance-pay-channel/'],
                                'icon' => 'fa fa-angle-right',
                            ],[
                                'label' => '配置对账表头',
                                'url' => ['/finance/finance-header/index'],
                                'icon' => 'fa fa-angle-right',
                            ],[
                            'label' => '开始对账',
                                'url' => ['/finance/finance-pop-order/'],
                                'icon' => 'fa fa-angle-right',
                            ],[
                                'label' => '查看历史对账记录',
                                'url' => ['/finance/finance-record-log/'],
                                'icon' => 'fa fa-angle-right',
                            ],[
                                'label' => '对账记录详情',
                                'url' => ['/finance/finance-pop-order/billinfo'],
                                'icon' => 'fa fa-angle-right',
                            ],[
                                'label' => '坏账管理',
                                'url' => ['/finance/finance-pop-order/bad'],
                                'icon' => 'fa fa-angle-right',
                            ]

                       ]

                    ],
                    [
                        'label' => '结算管理',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                        'options' => [
                            'class' => 'treeview ',
                        ],
                        'items'=>[
                           [
                                'label' => '自营结算',
                                'url' => ['#'],
                                'icon' => 'fa fa-angle-right',
                                'options' => [
                                    'class' => 'treeview ',
                                ],
                               'items'=>[
                                    [
                                        'label' => '全职结算',
                                        'url' => ['/finance/finance-settle-apply/self-fulltime-worker-settle-index?settle_type='.FinanceSettleApplySearch::SELF_FULLTIME_WORKER_SETTELE.'&review_section='.FinanceShopSettleApplySearch::BUSINESS_REVIEW],
                                        'icon' => 'fa fa-angle-right',
                                    ],
                                   [
                                        'label' => '兼职结算',
                                        'url' => ['/finance/finance-settle-apply/self-fulltime-worker-settle-index?settle_type='.FinanceSettleApplySearch::SELF_PARTTIME_WORKER_SETTELE.'&review_section='.FinanceShopSettleApplySearch::BUSINESS_REVIEW],
                                        'icon' => 'fa fa-angle-right',
                                    ],
                               ]
                            ],
                            [
                                'label' => '小家政结算',
                                'url' => ['#'],
                                'icon' => 'fa fa-angle-right',
                                'options' => [
                                    'class' => 'treeview ',
                                ],
                                'items'=>[
                                    [
                                        'label' => '门店结算',
                                        'url' => ['/finance/finance-shop-settle-apply/index?review_section='.FinanceShopSettleApplySearch::BUSINESS_REVIEW],
                                        'icon' => 'fa fa-angle-right',
                                    ],
                                   [
                                        'label' => '阿姨结算',
                                        'url' => ['/finance/finance-settle-apply/self-fulltime-worker-settle-index?settle_type='.FinanceSettleApplySearch::SHOP_WORKER_SETTELE.'&review_section='.FinanceShopSettleApplySearch::BUSINESS_REVIEW],
                                        'icon' => 'fa fa-angle-right',
                                    ],
                                ]
                            ],
                            [
                                'label' => '财务审核',
                                'url' => ['#'],
                                'icon' => 'fa fa-angle-right',
                                'options' => [
                                    'class' => 'treeview ',
                                ],
                                'items'=>[
                                    [
                                        'label' => '阿姨结算',
                                        'url' => ['/finance/finance-settle-apply/self-fulltime-worker-settle-index?settle_type='.FinanceSettleApplySearch::ALL_WORKER_SETTELE.'&review_section='.FinanceShopSettleApplySearch::FINANCE_REVIEW],
                                        'icon' => 'fa fa-angle-right',
                                    ],
                                    [
                                        'label' => '门店结算',
                                        'url' => ['/finance/finance-shop-settle-apply/index?review_section='.FinanceShopSettleApplySearch::FINANCE_REVIEW],
                                        'icon' => 'fa fa-angle-right',
                                    ],
                                ]
                            ], [
                                'label' => '结算查询',
                                 'url' => ['#'],
                                'icon' => 'fa fa-angle-right',
                                'options' => [
                                    'class' => 'treeview ',
                                ],
                                'items'=>[
                                    [
                                        'label' => '阿姨结算',
                                        'url' => ['/finance/finance-settle-apply/query'],
                                        'icon' => 'fa fa-angle-right',
                                    ],
                                    [
                                        'label' => '门店结算',
                                        'url' => ['/finance/finance-shop-settle-apply/query'],
                                        'icon' => 'fa fa-angle-right',
                                    ],
                                ]
                            ]
                        ],
                    ],
                    [
                        'label' => '退款管理',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                        'options' => [
                        'class' => 'treeview',
                        ],
                        'items' => [
                        [
                        'label' => '财务审核确认',
                        'url' => ['/finance/finance-refund/'],
                        'icon' => 'fa fa-angle-right',
                        ],
                        [
                        'label' => '退款统计',
                        'url' => ['/finance/finance-refund/countinfo'],
                        'icon' => 'fa fa-angle-right',
                        ]
                        ]

                    ],
                    [
                        'label' => '赔偿管理',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                        'options' => [
                        'class' => 'treeview',
                        ],
                        'items' => [
                        [
                        'label' => '财务确认赔偿',
                        'url' => ['/finance/finance-compensate/finance-confirm-index'],
                        'icon' => 'fa fa-angle-right',
                        ],
                        [
                        'label' => '赔偿查询',
                        'url' => ['/finance/finance-compensate/index'],
                        'icon' => 'fa fa-angle-right',
                        ],
                        ]

                    ],
                    [
                        'label' => '报表管理',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
						'options' => [
						'class' => 'treeview',
						],
						'items' => [
						[
						'label' => '日报表管理',
						'url' => ['/finance/finance-office-count/indexoffice'],
						'icon' => 'fa fa-angle-right',
						],
						]
                    ],
                    [
                        'label' => '发票管理',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
//                     [
//                         'label' => '线下运营（80%）',
//                         'url' => ['#'],
//                         'icon' => 'fa fa-angle-right',
//                          'options' => [
//                          'class' => 'treeview',
//                          ],
//                          'items' => [

//                          ]

//                     ]
                ],
            ],


//             [
//                 'label' => 'POP管理',
//                 'url' => ['#'],
//                 'icon' => 'fa fa-random',
//                 'options' => [
//                     'class' => 'treeview rootTree',
//                 ],
//                 'items' => [
//                     [
//                         'label' => '查看所有渠道',
//                         'url' => ['#'],
//                         'icon' => 'fa fa-angle-right',
//                     ],
//                     [
//                         'label' => '添加新渠道',
//                         'url' => ['#'],
//                         'icon' => 'fa fa-angle-right',
//                     ],
//                 ],
//             ],

        ]
    ]
);
