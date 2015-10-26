<?php
use common\widgets\Menu;
use core\models\finance\FinanceShopSettleApplySearch;
use core\models\finance\FinanceSettleApplySearch;

$ctrl = Yii::$app->controller;

echo Menu::widget(
    [
        'options' => [
            'class' => 'sidebar-menu'
        ],
        'items' => [
            [
                'label' => '家政公司管理(100%)',
                'url' => ['#'],
                'icon' => 'fa fa-slideshare',
                'options' => [
                    'class' => 'treeview rootTree',
                ],
                'visible' => (Yii::$app->user->can('housekeep')),
                'items' => [
                    [
                        'label' => '查看所有家政公司(100%)',
                        'url' => ['shopmanager/shop-manager/index'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '新合作公司(100%)',
                        'url' => ['shopmanager/shop-manager/create'],
                        'icon' => 'fa fa-angle-right',
                    ],
                ],
            ],
            [
                'label' => '门店管理(100%)',
                'url' => ['#'],
                'icon' => 'fa fa-flag',
                'visible' => (Yii::$app->user->can('shop')),
                'options' => [
                    'class' => 'treeview rootTree',
                ],
                'items' => [
                    [
                        'label' => '查看所有合作门店(100%)',
                        'url' => ['shop/shop/index'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '添加新门店(100%)',
                        'url' => ['shop/shop/create'],
                        'icon' => 'fa fa-angle-right',
                    ],
                ],
            ],
            [
                'label' => '阿姨管理(90%)',
                'url' => ['#'],
                'icon' => 'fa fa-female',
                'visible' => (Yii::$app->user->can('worker')),
                'options' => [
                    'class' => 'treeview rootTree',
                ],
                'items' => [
                    [
                        'label' => '查看所有阿姨(90%)',
                        'url' => ['/worker/worker'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '录入新阿姨(90%)',
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
                'label' => '客户管理(90%)',
                'url' => ['#'],
                'icon' => 'fa fa-user',
                'visible' => (Yii::$app->user->can('customer')),
                'options' => [
                    'class' => 'treeview rootTree',
                ],
                'items' => [
                    [
                        'label' => '查看所有顾客(90%)',
                        'url' => ['/customer/customer/index?CustomerSearch[is_del]=0'],
                        'icon' => 'fa fa-angle-right',
                    ],
                ],
            ],
            [
                'label' => '订单管理(50%)',
                'url' => ['#'],
                'icon' => 'fa fa-tag',
                'visible' => (Yii::$app->user->can('order')),
                'options' => [
                    'class' => 'treeview rootTree',
                ],
                'items' => [
                    [
                        'label' => '查看所有订单(20%)',
                        'url' => ['/order/order'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '创建新订单(95%)',
                        'url' => ['/order/order/create'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '人工派单(90%)',
                        'url' => ['/order/order/assign'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '智能派单(90%)',
                        'url' => ['/order/auto-assign'],
                        'icon' => 'fa fa-angle-right',
                    ],
                	[
                		'label' => '订单投诉(30%)',
                		'url' => ['/order-complaint'],
                		'icon' => 'fa fa-angle-right',
                	],
                ],
            ],
            [
                'label' => '运营管理',
                'url' => ['#'],
                'icon' => 'fa fa-recycle',
                'visible' => (Yii::$app->user->can('operation')),
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
                        'label' => '服务和品类管理(80%)',
                        'url' => ['/operation/operation-category/'],
                        'icon' => 'fa fa-angle-right',

                    ],
                    [
                        'label' => '城市和商圈管理(90%)',
                        'url' => ['/operation/operation-city'],
                        'icon' => 'fa fa-angle-right',

                    ],
//                    [
//                        'label' => '上线城市(80%)',
//                        'url' => ['/operation-city/release'],
//                        'icon' => 'fa fa-angle-right',
//                        'visible' => (Yii::$app->user->identity->username == 'admin'),
//                    ],
                    [
                        'label' => '已开通城市管理',
                        'url' => ['/operation/operation-city/opencity'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => 'CMS管理(90%)',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                        'options' => [
                            'class' => 'treeview ',
                        ],
                        'items' => [
                            [
                                'label' => '平台管理',
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
                                'label' => '活动内容管理',
                                'url' => ['/operation/operation-advert-content'],
                                'icon' => 'fa fa-angle-right',
                                
                            ],
                            [
                                'label' => '广告发布',
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
                        'label' => '促销管理(10%)',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                        'options' => [
                            'class' => 'treeview ',
                        ],
                        'items' => [
                            [
                                'label' => '优惠券列表(10%)',
                                'url' => ['operation/coupon/coupon/index'],
                                'icon' => 'fa fa-angle-right',
                            ],
                            [
                                'label' => '添加新优惠券(10%)',
                                'url' => ['operation/coupon/coupon/create'],
                                'icon' => 'fa fa-angle-right',
                            ],
                            [
                                'label' => '绑定用户',
                                'url' => ['operation/coupon/coupon/bundle'],
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
                        'label' => '启动页管理(90%)',
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
                        'label' => '阿姨任务管理(95%)',
                        'url' => ['/worker/worker-task/index'],
                        'icon' => 'fa fa-angle-right',
                    ],
					[
						'label' => '服务卡管理',
						'url' => ['#'],
						'icon' => 'fa fa-angle-right',
						'options' => [
							'class' => 'treeview active',
						],
						'items' => [
							[
								'label' => '服务卡信息管理',
								'url' => ['operation/operation-server-card/index'],
								'icon' => 'fa fa-angle-right',
							],
							[
								'label' => '客户服务卡管理',
								'url' => ['operation/operation-server-card-customer/index'],
								'icon' => 'fa fa-angle-right',
							],
							[
								'label' => '服务卡付款管理',
								'url' => ['#'],
								'icon' => 'fa fa-angle-right',
							],
                ],
            ],
                    [
                        'label' => '精品保洁管理',
                        'url' => ['/operation/operation-selected-service'],
                        'icon' => 'fa fa-angle-right',
                    ],
                ],
            ],
            [
                'label' => '财务管理',
                'url' => ['#'],
                'icon' => 'fa fa-credit-card',
                'visible' => (Yii::$app->user->can('finance')),
                'options' => [
                    'class' => 'treeview rootTree',
                ],
                'items' => [
                    [
                        'label' => '对账管理(90%)',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                        'options' => [
                            'class' => 'treeview ',
                        ],
                        'items' => [
                            [
                                'label' => '渠道管理(95%)',
                                'url' => ['/finance/finance-pay-channel/'],
                                'icon' => 'fa fa-angle-right',
                            ],[
                                'label' => '配置对账表头(95%)',
                                'url' => ['/finance/finance-header/index'],
                                'icon' => 'fa fa-angle-right',
                            ],[
                            'label' => '开始对账(85%)',
                                'url' => ['/finance/finance-pop-order/'],
                                'icon' => 'fa fa-angle-right',
                            ],[
                                'label' => '查看历史对账记录(95%)',
                                'url' => ['/finance/finance-record-log/'],
                                'icon' => 'fa fa-angle-right',
                            ],[
                                'label' => '对账记录详情(90%)',
                                'url' => ['/finance/finance-pop-order/billinfo'],
                                'icon' => 'fa fa-angle-right',
                            ],[
                                'label' => '坏账管理(90%)',
                                'url' => ['/finance/finance-pop-order/bad'],
                                'icon' => 'fa fa-angle-right',
                            ]
        
                       ]

                    ],
                    [
                        'label' => '结算管理(92%)',
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
                                        'label' => '全职结算(95%)',
                                        'url' => ['/finance/finance-settle-apply/self-fulltime-worker-settle-index?settle_type='.FinanceSettleApplySearch::SELF_FULLTIME_WORKER_SETTELE.'&review_section='.FinanceShopSettleApplySearch::BUSINESS_REVIEW],
                                        'icon' => 'fa fa-angle-right',
                                    ],
                                   [
                                        'label' => '兼职结算(95%)',
                                        'url' => ['/finance/finance-settle-apply/self-fulltime-worker-settle-index?settle_type='.FinanceSettleApplySearch::SELF_PARTTIME_WORKER_SETTELE.'&review_section='.FinanceShopSettleApplySearch::BUSINESS_REVIEW],
                                        'icon' => 'fa fa-angle-right',
                                    ],
                               ]
                            ],
                            [
                                'label' => '小家政结算(95%)',
                                'url' => ['#'],
                                'icon' => 'fa fa-angle-right',
                                'options' => [
                                    'class' => 'treeview ',
                                ],
                                'items'=>[
                                    [
                                        'label' => '门店结算(95%)',
                                        'url' => ['/finance/finance-shop-settle-apply/index?review_section='.FinanceShopSettleApplySearch::BUSINESS_REVIEW],
                                        'icon' => 'fa fa-angle-right',
                                    ],
                                   [
                                        'label' => '阿姨结算(95%)',
                                        'url' => ['/finance/finance-settle-apply/self-fulltime-worker-settle-index?settle_type='.FinanceSettleApplySearch::SHOP_WORKER_SETTELE.'&review_section='.FinanceShopSettleApplySearch::BUSINESS_REVIEW],
                                        'icon' => 'fa fa-angle-right',
                                    ],
                                ]
                            ],
                            [
                                'label' => '财务审核(95%)',
                                'url' => ['#'],
                                'icon' => 'fa fa-angle-right',
                                'options' => [
                                    'class' => 'treeview ',
                                ],
                                'items'=>[
                                    [
                                        'label' => '阿姨结算(95%)',
                                        'url' => ['/finance/finance-settle-apply/self-fulltime-worker-settle-index?settle_type='.FinanceSettleApplySearch::ALL_WORKER_SETTELE.'&review_section='.FinanceShopSettleApplySearch::FINANCE_REVIEW],
                                        'icon' => 'fa fa-angle-right',
                                    ],
                                    [
                                        'label' => '门店结算(95%)',
                                        'url' => ['/finance/finance-shop-settle-apply/index?review_section='.FinanceShopSettleApplySearch::FINANCE_REVIEW],
                                        'icon' => 'fa fa-angle-right',
                                    ],
                                ]
                            ], [
                                'label' => '结算查询(90%)',
                                 'url' => ['#'],
                                'icon' => 'fa fa-angle-right',
                                'options' => [
                                    'class' => 'treeview ',
                                ],
                                'items'=>[
                                    [
                                        'label' => '阿姨结算(90%)',
                                        'url' => ['/finance/finance-settle-apply/query'],
                                        'icon' => 'fa fa-angle-right',
                                    ],
                                    [
                                        'label' => '门店结算(90%)',
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
                        'label' => '确认退款审核(80%)',
                        'url' => ['/finance/finance-refund/'],
                        'icon' => 'fa fa-angle-right',
                        ],
                        [
                        'label' => '退款统计(80%)',
                        'url' => ['/finance/finance-refund/countinfo'],
                        'icon' => 'fa fa-angle-right',
                        ]
                        ]
                            
                    ],
                    [
                        'label' => '赔偿管理(95%)',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                        'options' => [
                        'class' => 'treeview',
                        ],
                        'items' => [
                        [
                        'label' => '财务确认赔偿(95%)',
                        'url' => ['/finance/finance-compensate/finance-confirm-index'],
                        'icon' => 'fa fa-angle-right',
                        ],
                        [
                        'label' => '赔偿查询(95%)',
                        'url' => ['/finance/finance-compensate/index'],
                        'icon' => 'fa fa-angle-right',
                        ],
                        ]
                            
                    ],
                    [
                        'label' => '报表管理(0%)',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '发票管理(5%)',
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

            [
                'label' => '供应商管理',
                'url' => ['#'],
                'icon' => 'fa fa-ambulance',
                'visible' => (Yii::$app->user->can('supplier')),
                'options' => [
                    'class' => 'treeview rootTree',
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
                        'label' => '商品供应商',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '服务供应商',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    
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
