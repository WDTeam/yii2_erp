<style type="text/css">
.skin-blue .sidebar>.sidebar-menu>li:hover {
	color: #f6a202 !important;
}

ul.sidebar-menu ul.treeview-menu li.active a.active {
	color: #f6a202;
	font-weight: bold;
}

.sidebar .sidebar-menu .treeview-menu>li>a:hover {
	color: #f6a202;
	font-weight: bold;
}

.skin-blue .sidebar>.sidebar-menu>li>a:hover, .skin-blue .sidebar>.sidebar-menu>li.active>a
	{
	color: #f6a202;
	font-weight: bold;
}
</style>
<?php
use boss\widgets\Menu;
use core\models\finance\FinanceShopSettleApplySearch;
use boss\components\RbacHelper;

$ctrl = Yii::$app->controller;

echo Menu::widget(
    [
        'options' => [
            'class' => 'sidebar-menu'
        ],
        'items' => RbacHelper::menu(
            [
                [
                    'label' => '家政公司管理',
                    'url' => [
                        '#'
                    ],
                    'icon' => 'fa fa-slideshare',
                    'options' => [
                        'class' => 'treeview rootTree'
                    ],
                    'can' => 'sidebar_housekeep',
                    'items' => [
                        [
                            'label' => '查看所有家政公司',
                            'url' => [
                                'shopmanager/shop-manager/index'
                            ],
                            'icon' => 'fa fa-angle-right'
                        ],
                        [
                            'label' => '添加新家政',
                            'url' => [
                                'shopmanager/shop-manager/create'
                            ],
                            'icon' => 'fa fa-angle-right'
                        ]
                    ]
                ],
                [
                    'label' => '门店管理',
                    'url' => [
                        '#'
                    ],
                    'icon' => 'fa fa-flag',
                    'can' => 'sidebar_shop',
                    'options' => [
                        'class' => 'treeview rootTree'
                    ],
                    'items' => [
                        [
                            'label' => '查看所有门店',
                            'url' => [
                                'shop/shop/index'
                            ],
                            'icon' => 'fa fa-angle-right'
                        ],
                        [
                            'label' => '添加新门店',
                            'url' => [
                                'shop/shop/create'
                            ],
                            'icon' => 'fa fa-angle-right'
                        ]
                    ]
                ],
                [
                    'label' => '阿姨管理',
                    'url' => [
                        '#'
                    ],
                    'icon' => 'fa fa-female',
                    'can' => 'sidebar_worker',
                    'options' => [
                        'class' => 'treeview rootTree'
                    ],
                    'items' => [
                        [
                            'label' => '查看所有阿姨',
                            'url' => [
                                'worker/worker/index'
                            ],
                            'icon' => 'fa fa-angle-right'
                        ],
                        [
                            'label' => '录入新阿姨',
                            'url' => [
                                'worker/worker/create'
                            ],
                            'icon' => 'fa fa-angle-right'
                        ]
                    ]
                ],
                [
                    'label' => '客户管理',
                    'url' => [
                        '#'
                    ],
                    'icon' => 'fa fa-user',
                    'can' => 'sidebar_customer',
                    'options' => [
                        'class' => 'treeview rootTree'
                    ],
                    'items' => [
                        [
                            'label' => '查看所有客户',
                            'url' => [
                                'customer/customer/index',
                                'CustomerSearch' => [
                                    'is_del' => 0
                                ]
                            ],
                            'icon' => 'fa fa-angle-right'
                        ],
                        [
                            'label' => '评价列表',
                            'url' => [
                                'customer/customer-comment'
                            ],
                            'icon' => 'fa fa-angle-right'
                        ],
                        [
                            'label' => '评价标签管理',
                            'url' => [
                                'customer/customer-comment-tag'
                            ],
                            'icon' => 'fa fa-angle-right'
                        ]
                    ]
                ],
                [
                    'label' => '订单管理',
                    'url' => [
                        '#'
                    ],
                    'icon' => 'fa fa-tag',
                    'can' => 'sidebar_order',
                    'options' => [
                        'class' => 'treeview rootTree'
                    ],
                    'items' => [
                        [
                            'label' => '查看所有订单',
                            'url' => [
                                'order/order/index'
                            ],
                            'icon' => 'fa fa-angle-right'
                        ],
                        [
                            'label' => '人工下单',
                            'url' => [
                                'order/order/create'
                            ],
                            'icon' => 'fa fa-angle-right'
                        ],
                        [
                            'label' => '人工派单',
                            'url' => [
                                'order/order/assign'
                            ],
                            'icon' => 'fa fa-angle-right'
                        ],
                        [
                            'label' => '智能派单',
                            'url' => [
                                'order/auto-assign/index'
                            ],
                            'icon' => 'fa fa-angle-right'
                        ],
                        [
                            'label' => '处理投诉订单',
                            'url' => [
                                'order/order-complaint/index'
                            ],
                            'icon' => 'fa fa-angle-right'
                        ],
                        [
                            'label' => '响应异常订单',
                            'url' => [
                                'order/order-response/index'
                            ],
                            'icon' => 'fa fa-angle-right'
                        ]
                    ]
                ]
                ,
                [
                    'label' => '交易管理',
                    'url' => [
                        '#'
                    ],
                    'icon' => 'fa fa-tag',
                    'can' => 'sidebar_payment',
                    'options' => [
                        'class' => 'treeview rootTree'
                    ],
                    'items' => [
                        [
                            'label' => '第三方支付记录查询',
                            'url' => [
                                'payment/payment/index'
                            ],
                            'icon' => 'fa fa-angle-right'
                        ],
                        [
                            'label' => 'E家洁交易记录查询',
                            'url' => [
                                'payment/payment-customer-trans-record/index'
                            ],
                            'icon' => 'fa fa-angle-right'
                        ]
                    ]
                ],
                [
                    'label' => '运营管理',
                    'url' => [
                        '#'
                    ],
                    'icon' => 'fa fa-recycle',
                    'can' => 'sidebar_operation',
                    'options' => [
                        'class' => 'treeview rootTree'
                    ],
                    'items' => [
                        [
                            'label' => '服务管理',
                            'url' => [
                                '#'
                            ],
                            'icon' => 'fa fa-angle-right',
                            'can' => 'sidebar_operation_server',
                            'options' => [
                                'class' => 'treeview '
                            ],
                            'items' => [
                                [
                                    'label' => '配置服务品类和服务项目',
                                    'url' => [
                                        'operation/operation-category/index'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ],
                                [
                                    'label' => '配置城市和商圈信息',
                                    'url' => [
                                        'operation/operation-city/index'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ],
                                [
                                    'label' => '配置保洁任务信息',
                                    'url' => [
                                        'operation/operation-selected-service/index'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ],
                                [
                                    'label' => '已开通城市服务管理',
                                    'url' => [
                                        'operation/operation-shop-district-goods/index'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ]
                            ]
                            
                        ],
                        [
                            'label' => 'CMS管理',
                            'url' => [
                                '#'
                            ],
                            'icon' => 'fa fa-angle-right',
                            'can' => 'sidebar_operation_cms',
                            'options' => [
                                'class' => 'treeview '
                            ],
                            'items' => [
                                [
                                    'label' => '配置广告平台',
                                    'url' => [
                                        'operation/operation-platform/index'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ],
                                [
                                    'label' => '配置广告位置',
                                    'url' => [
                                        'operation/operation-advert-position/index'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ],
                                [
                                    'label' => '配置广告内容',
                                    'url' => [
                                        'operation/operation-advert-content/index'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ],
                                [
                                    'label' => '已发布广告管理',
                                    'url' => [
                                        'operation/operation-advert-release/index'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ]
                            ]
                            
                        ],
                        [
                            'label' => '优惠券管理',
                            'url' => [
                                '#'
                            ],
                            'icon' => 'fa fa-angle-right',
                            'can' => 'sidebar_operation_coupon',
                            'options' => [
                                'class' => 'treeview '
                            ],
                            'items' => [
                                [
                                    'label' => '规则管理',
                                    'url' => [
                                        'operation/coupon/coupon-rule/index'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ],
                                [
                                    'label' => '用户管理',
                                    'url' => [
                                        'operation/coupon/coupon-userinfo/index'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ]
                            ]
                        ]
                        ,
                        [
                            'label' => '启动页管理',
                            'url' => [
                                'operation/operation-boot-page/index'
                            ],
                            'icon' => 'fa fa-angle-right'
                        ],
                        [
                            'label' => '阿姨任务管理',
                            'url' => [
                                'operation/worker-task/index'
                            ],
                            'icon' => 'fa fa-angle-right'
                        ],
                        
                        [
                            'label' => '渠道管理',
                            'url' => [
                                '#'
                            ],
                            'icon' => 'fa fa-angle-right',
                            'can'=>'sidebar_operation_chnnel',
                            'options' => [
                                'class' => 'treeview '
                            ],
                            'items' => [
                                [
                                    'label' => '订单渠道管理',
                                    'url' => [
                                        'operation/operation-order-channel'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ],
                                [
                                    'label' => '支付渠道管理',
                                    'url' => [
                                        'operation/operation-pay-channel'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ]
                            ]
                        ],
                        
                        [
                            'label' => '服务卡管理',
                            'url' => [
                                '#'
                            ],
                            'icon' => 'fa fa-angle-right',
                            'can'=>'sidebar_operation_server_card',
                            'options' => [
                                'class' => 'treeview'
                            ],
                            'items' => [
                                [
                                    'label' => '服务卡信息管理',
                                    'url' => [
                                        'operation/operation-service-card-info/index'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ],
                                [
                                    'label' => '服务卡销售记录',
                                    'url' => [
                                        'operation/operation-service-card-sell-record/index'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ],
                                [
                                    'label' => '服务卡客户关系',
                                    'url' => [
                                        'operation/operation-service-card-with-customer/index'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ],
                                [
                                    'label' => '服务卡消费记录',
                                    'url' => [
                                        'operation/operation-service-card-consume-record/index'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ]
                            ]
                        ]
                        
                    ]
                    
                ],
                
                [
                    'label' => '财务管理',
                    'url' => [
                        '#'
                    ],
                    'icon' => 'fa fa-credit-card',
                    'can' => 'sidebar_finance',
                    'options' => [
                        'class' => 'treeview rootTree'
                    ],
                    'items' => [
                        [
                            'label' => '对账管理',
                            'url' => [
                                '#'
                            ],
                            'icon' => 'fa fa-angle-right',
                            'can'=>'sidebar_finance_duizhang',
                            'options' => [
                                'class' => 'treeview '
                            ],
                            'items' => [
                                [
                                    'label' => '配置对账表头',
                                    'url' => [
                                        'finance/finance-header/index'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ],
                                [
                                    'label' => '开始对账',
                                    'url' => [
                                        'finance/finance-pop-order/index'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ],
                                [
                                    'label' => '查看历史对账记录',
                                    'url' => [
                                        'finance/finance-record-log/index'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ],
                                [
                                    'label' => '对账记录详情',
                                    'url' => [
                                        'finance/finance-pop-order/billinfo'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ],
                                [
                                    'label' => '坏账管理',
                                    'url' => [
                                        'finance/finance-pop-order/bad'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ]
                            ]
                        ]
                        ,
                        [
                            'label' => '结算管理',
                            'url' => [
                                '#'
                            ],
                            'icon' => 'fa fa-angle-right',
                            'can'=>'sidebar_finance_jiesuan',
                            'options' => [
                                'class' => 'treeview '
                            ],
                            'items' => [
                                [
                                    'label' => '自营结算',
                                    'url' => [
                                        '#'
                                    ],
                                    'icon' => 'fa fa-angle-right',
                                    'can'=>'sidebar_finance_jiesuan_owner',
                                    'options' => [
                                        'class' => 'treeview '
                                    ],
                                    'items' => [
                                        [
                                            'label' => '全职结算',
                                            'url' => [
                                                'finance/finance-worker-settle-apply/self-fulltime-index'
                                            ],
                                            'icon' => 'fa fa-angle-right'
                                        ],
                                        [
                                            'label' => '兼职结算',
                                            'url' => [
                                                'finance/finance-worker-settle-apply/self-parttime-index'
                                            ],
                                            'icon' => 'fa fa-angle-right'
                                        ]
                                    ]
                                ],
                                [
                                    'label' => '小家政结算',
                                    'url' => [
                                        '#'
                                    ],
                                    'icon' => 'fa fa-angle-right',
                                    'can'=>'sidebar_finance_jiesuan_shopmanager',
                                    'options' => [
                                        'class' => 'treeview '
                                    ],
                                    'items' => [
                                        [
                                            'label' => '门店结算',
                                            'url' => [
                                                'finance/finance-shop-settle-apply/index'
                                            ],
                                            'icon' => 'fa fa-angle-right'
                                        ],
                                        [
                                            'label' => '阿姨结算',
                                            'url' => [
                                                'finance/finance-worker-settle-apply/shop-worker-index'
                                            ],
                                            'icon' => 'fa fa-angle-right'
                                        ]
                                    ]
                                ],
                                [
                                    'label' => '财务审核',
                                    'url' => [
                                        '#'
                                    ],
                                    'icon' => 'fa fa-angle-right',
                                    'can'=>'sidebar_finance_settle',
                                    'options' => [
                                        'class' => 'treeview '
                                    ],
                                    'items' => [
                                        [
                                            'label' => '阿姨结算',
                                            'url' => [
                                                'finance/finance-worker-settle-apply/finance-settle-worker-index'
                                            ],
                                            'icon' => 'fa fa-angle-right'
                                        ],
                                        [
                                            'label' => '门店结算',
                                            'url' => [
                                                'finance/finance-shop-settle-apply/finance-check-index'
                                            ],
                                            'icon' => 'fa fa-angle-right'
                                        ]
                                    ]
                                ],
                                [
                                    'label' => '结算查询',
                                    'url' => [
                                        '#'
                                    ],
                                    'icon' => 'fa fa-angle-right',
                                    'can'=>'sidebar_finance_query',
                                    'options' => [
                                        'class' => 'treeview '
                                    ],
                                    'items' => [
                                        [
                                            'label' => '阿姨结算',
                                            'url' => [
                                                'finance/finance-worker-settle-apply/query'
                                            ],
                                            'icon' => 'fa fa-angle-right'
                                        ],
                                        [
                                            'label' => '门店结算',
                                            'url' => [
                                                'finance/finance-shop-settle-apply/query'
                                            ],
                                            'icon' => 'fa fa-angle-right'
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            'label' => '退款管理',
                            'url' => [
                                '#'
                            ],
                            'icon' => 'fa fa-angle-right',
                            'can'=>'sidebar_finance_refund',
                            'options' => [
                                'class' => 'treeview'
                            ],
                            'items' => [
                                [
                                    'label' => '财务审核确认',
                                    'url' => [
                                        'finance/finance-refund/'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ],
                                [
                                    'label' => '退款统计',
                                    'url' => [
                                        'finance/finance-refund/countinfo'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ]
                            ]
                        ],
                        [
                            'label' => '赔偿管理',
                            'url' => [
                                '#'
                            ],
                            'icon' => 'fa fa-angle-right',
                            'can'=>'sidebar_finance_compensate',
                            'options' => [
                                'class' => 'treeview'
                            ],
                            'items' => [
                                [
                                    'label' => '财务确认赔偿',
                                    'url' => [
                                        'finance/finance-compensate/finance-confirm-index'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ],
                                [
                                    'label' => '赔偿查询',
                                    'url' => [
                                        'finance/finance-compensate/index'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ]
                            ]
                        ],
                        [
                            'label' => '报表管理',
                            'url' => [
                                '#'
                            ],
                            'icon' => 'fa fa-angle-right',
                            'can'=>'sidebar_finance_office',
                            'options' => [
                                'class' => 'treeview'
                            ],
                            'items' => [
                                [
                                    'label' => '日报表管理',
                                    'url' => [
                                        'finance/finance-office-count/indexoffice'
                                    ],
                                    'icon' => 'fa fa-angle-right'
                                ]
                            ]
                        ],
                        [
                            'label' => '发票管理',
                            'url' => [
                                '#'
                            ],
                            'icon' => 'fa fa-angle-right',
                            'can'=>'sidebar_finance_fabiao',
                        ]
                    ]
                ]
            ])
    ]);
