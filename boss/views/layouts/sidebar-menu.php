<?php
use common\widgets\Menu;

$ctrl = Yii::$app->controller;

echo Menu::widget(
    [
        'options' => [
            'class' => 'sidebar-menu'
        ],
        'items' => [
            [
                'label' => '家政公司管理(90%)',
                'url' => ['#'],
                'icon' => 'fa fa-slideshare',
                'options' => [
                    'class' => 'treeview rootTree',
                ],
                'items' => [
                    [
                        'label' => '查看所有家政公司(80%)',
                        'url' => ['shop-manager/index'],
                        'icon' => 'fa fa-angle-right',
                    ],
                    [
                        'label' => '新合作公司(100%)',
                        'url' => ['shop-manager/create'],
                        'icon' => 'fa fa-angle-right',
                    ],
                ],
            ],
            [
                'label' => '门店管理(95%)',
                'url' => ['#'],
                'icon' => 'fa fa-flag',
                'visible' => (Yii::$app->user->identity->username == 'admin'),
                'options' => [
                    'class' => 'treeview rootTree',
                ],
                'items' => [
                    [
                        'label' => '查看所有合作门店(90%)',
                        'url' => ['shop/index'],
                        'icon' => 'fa fa-angle-right',
                        'visible' => (Yii::$app->user->identity->username == 'admin'),
                    ],
                    [
                        'label' => '查看所有自营门店(100%)',
                        'url' => ['shop/index', 'ShopSearch'=>['shop_manager_id'=>1]],
                        'icon' => 'fa fa-angle-right',
                        'visible' => (Yii::$app->user->identity->username == 'admin'),
                    ],
                    [
                        'label' => '添加新门店(100%)',
                        'url' => ['shop/create'],
                        'icon' => 'fa fa-angle-right',
                        'visible' => (Yii::$app->user->identity->username == 'admin'),
                    ],
                ],
            ],
            [
                'label' => '阿姨管理(40%)',
                'url' => ['#'],
                'icon' => 'fa fa-female',
                'visible' => (Yii::$app->user->identity->username == 'admin'),
                'options' => [
                    'class' => 'treeview rootTree',
                ],
                'items' => [
                    [
                        'label' => '查看所有阿姨(50%)',
                        'url' => ['/worker'],
                        'icon' => 'fa fa-angle-right',
                        'visible' => (Yii::$app->user->identity->username == 'admin'),
                    ],
                    [
                        'label' => '录入新阿姨(80%)',
                        'url' => ['/worker/create'],
                        'icon' => 'fa fa-angle-right',
                        'visible' => (Yii::$app->user->identity->username == 'admin'),
                    ],
//                     [
//                         'label' => '阿姨黑名单(待定)',
//                         'url' => ['/worker/index?WorkerSearch[worker_is_blacklist]=1'],
//                         'icon' => 'fa fa-angle-right',
//                     ],
                ],
            ],
            [
                'label' => '客户管理(75%)',
                'url' => ['#'],
                'icon' => 'fa fa-user',
                'visible' => (Yii::$app->user->identity->username == 'admin'),
                'options' => [
                    'class' => 'treeview rootTree',
                ],
                'items' => [
                    [
                        'label' => '查看所有顾客(80%)',
                        'url' => ['/customer/index?CustomerSearch[is_del]=0'],
                        'icon' => 'fa fa-angle-right',
                        'visible' => (Yii::$app->user->identity->username == 'admin'),
                    ],
                    [
                        'label' => '管理顾客黑名单(80%)',
                        'url' => ['/customer/block?CustomerSearch[is_del]=1'],
                        'icon' => 'fa fa-angle-right',
                        'visible' => (Yii::$app->user->identity->username == 'admin'),
                    ],
                ],
            ],
            [
                'label' => '订单管理(20%)',
                'url' => ['#'],
                'icon' => 'fa fa-tag',
                'visible' => (Yii::$app->user->identity->username == 'admin'),
                'options' => [
                    'class' => 'treeview rootTree',
                ],
                'items' => [
                    [
                        'label' => '查看所有订单(20%)',
                        'url' => ['/order'],
                        'icon' => 'fa fa-angle-right',
                        'visible' => (Yii::$app->user->identity->username == 'admin'),
                    ],
                    [
                        'label' => '创建新订单(80%)',
                        'url' => ['/order/create'],
                        'icon' => 'fa fa-angle-right',
                        'visible' => (Yii::$app->user->identity->username == 'admin'),
                    ],
                    [
                        'label' => '人工派单(0%)',
                        'url' => ['/manual-order/index'],
                        'icon' => 'fa fa-angle-right',
                        'visible' => (Yii::$app->user->identity->username == 'admin'),
                    ],
                    [
                        'label' => '智能派单(0%)',
                        'url' => ['/auto-order/index'],
                        'icon' => 'fa fa-angle-right',
                        'visible' => (Yii::$app->user->identity->username == 'admin'),
                    ],
                ],
            ],
            [
                'label' => '运营管理',
                'url' => ['#'],
                'icon' => 'fa fa-recycle',
                'visible' => (Yii::$app->user->identity->username == 'admin'),
                'options' => [
                    'class' => 'treeview rootTree',
                ],
                'items' => [
                    [
                        'label' => '用户运营(0%)',
                        'url' => ['/operation-category'],
                        'icon' => 'fa fa-angle-right',
                        'visible' => (Yii::$app->user->identity->username == 'admin'),
                    ],
                    [
                        'label' => '阿姨运营(0%)',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                        'visible' => (Yii::$app->user->identity->username == 'admin'),
                    ],
                    [
                        'label' => '企业运营(0%)',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                        'visible' => (Yii::$app->user->identity->username == 'admin'),
                    ],
                    [
                        'label' => 'CMS管理(80%)',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                        'visible' => (Yii::$app->user->identity->username == 'admin'),
                        'options' => [
                            'class' => 'treeview ',
                        ],
                        'items' => [
                            [
                                'label' => '平台管理',
                                'url' => ['/operation-platform'],
                                'icon' => 'fa fa-angle-right',
                                'visible' => (Yii::$app->user->identity->username == 'admin'),
                            ],
                            //                            [
                                //                                'label' => '系统版本管理',
                                //                                'url' => ['/operation-platform-version'],
                                //                                'icon' => 'fa fa-angle-right',
                                //                            ],
                            [
                                'label' => '广告位置管理',
                                'url' => ['/operation-advert-position'],
                                'icon' => 'fa fa-angle-right',
                                'visible' => (Yii::$app->user->identity->username == 'admin'),
                            ],
                            [
                                'label' => '活动内容管理',
                                'url' => ['/operation-advert-content'],
                                'icon' => 'fa fa-angle-right',
                                'visible' => (Yii::$app->user->identity->username == 'admin'),
                            ],
                            [
                                'label' => '广告发布',
                                'url' => ['/operation-advert-release'],
                                'icon' => 'fa fa-angle-right',
                                'visible' => (Yii::$app->user->identity->username == 'admin'),
                            ],
                        ]
                    ],
                    [
                        'label' => '通知管理(0%)',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                        'visible' => (Yii::$app->user->identity->username == 'admin'),
                    ],
                    [
                        'label' => '促销管理(0%)',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                        'visible' => (Yii::$app->user->identity->username == 'admin'),
                    ],
                    [
                        'label' => '城市管理(90%)',
                        'url' => ['/operation-city'],
                        'icon' => 'fa fa-angle-right',
                        'visible' => (Yii::$app->user->identity->username == 'admin'),
                    ],
//                     [

//                        'label' => '商圈管理(40%)',
//                        'url' => ['/operation-shop-district'],
//                        'icon' => 'fa fa-angle-right',
//                        'visible' => (Yii::$app->user->identity->username == 'admin'),
//                    ],
                    [
                        'label' => '商品管理(20%)',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                        'visible' => (Yii::$app->user->identity->username == 'admin'),
                        'options' => [
                            'class' => 'treeview ',
                        ],
                        'items' => [
                            [
                                'label' => '商品管理(20%)',
                                'url' => ['/operation-goods'],
                                'icon' => 'fa fa-angle-right',
                                'visible' => (Yii::$app->user->identity->username == 'admin'),
                            ],
//                            [
//                                'label' => '价格策略管理(20%)',
//                                'url' => ['/operation-price-strategy'],
//                                'icon' => 'fa fa-angle-right',
//                                'visible' => (Yii::$app->user->identity->username == 'admin'),
//                            ],
                            [
                                'label' => '规格管理(20%)',
                                'url' => ['/operation-spec'],
                                'icon' => 'fa fa-angle-right',
                                'visible' => (Yii::$app->user->identity->username == 'admin'),
                            ],
                            [
                                'label' => '服务管理(90%)',
                                'url' => ['/operation-category'],
                                'icon' => 'fa fa-angle-right',
                                'visible' => (Yii::$app->user->identity->username == 'admin'),
                            ],
                        ]
                    ],
                    [
                        'label' => '启动页管理(90%)',
                        'url' => ['/operation-boot-page'],
                        'icon' => 'fa fa-angle-right',
                        'visible' => (Yii::$app->user->identity->username == 'admin'),
                    ],
                    /**[
                     'label' => '引导页管理(0%)',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                     * 'visible' => (Yii::$app->user->identity->username == 'admin'),
                    ],
                    [
                        'label' => '话术管理(0%)',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                     * 'visible' => (Yii::$app->user->identity->username == 'admin'),
                    ],*/
                ],
            ],
            [
                'label' => '财务管理',
                'url' => ['#'],
                'icon' => 'fa fa-credit-card',
                'visible' => (Yii::$app->user->identity->username == 'admin'),
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
    							'url' => ['/finance-order-channel/'],
    							'icon' => 'fa fa-angle-right',
							],[
    							'label' => '配置对账表头(95%)',
    							'url' => ['/finance-header/index'],
    							'icon' => 'fa fa-angle-right',
							],[
							'label' => '开始对账(85%)',
    							'url' => ['/finance-pop-order/'],
    							'icon' => 'fa fa-angle-right',
							],[
    							'label' => '查看历史对账记录(95%)',
    							'url' => ['/finance-record-log/'],
    							'icon' => 'fa fa-angle-right',
							],[
    							'label' => '对账记录详情(90%)',
    							'url' => ['/finance-pop-order/billinfo'],
    							'icon' => 'fa fa-angle-right',
							],[
    							'label' => '坏账管理(90%)',
    							'url' => ['/finance-pop-order/bad'],
    							'icon' => 'fa fa-angle-right',
							]
		
					   ]

                    ],
                    [
                        'label' => '结算管理(85%)',
                        'url' => ['#'],
                        'icon' => 'fa fa-angle-right',
                        'options' => [
                            'class' => 'treeview ',
                        ],
                        'items'=>[
                           [
                                'label' => '阿姨结算',
                                'url' => ['/finance-settle-apply/worker-manual-settlement-index'],
                                'icon' => 'fa fa-angle-right',
                            ],
                            [
                                'label' => '小家政结算',
                                'url' => ['/finance-settle-apply/homemaking-manual-settlement-index'],
                                'icon' => 'fa fa-angle-right',
                            ],
                            [
                                'label' => '结算审核(85%)',
                                'url' => ['/finance-settle-apply/index?FinanceSettleApplySearch[finance_settle_apply_status]=0&FinanceSettleApplySearch[ids]=&FinanceSettleApplySearch[nodeId]=1'],
                                'icon' => 'fa fa-angle-right',
                            ], [
                                'label' => '结算查询',
                                'url' => ['/finance-settle-apply/query'],
                                'icon' => 'fa fa-angle-right',
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
                        'label' => '确认退款审核(5%)',
                        'url' => ['/finance-refund/'],
                        'icon' => 'fa fa-angle-right',
                        ],
                        [
                        'label' => '退款确认退款审批(5%)',
                        'url' => ['/finance-refund/'],
                        'icon' => 'fa fa-angle-right',
                        ],[
                        'label' => '会计执行银行退款(5%)',
                        'url' => ['/finance-refund/'],
                        'icon' => 'fa fa-angle-right',
                        ],[
                        'label' => '确认银行退款(5%)',
                        'url' => ['/finance-refund/'],
                        'icon' => 'fa fa-angle-right',
                        ],[
                        'label' => '退款详情(5%)',
                        'url' => ['/finance-refund/'],
                        'icon' => 'fa fa-angle-right',
                        ],[
                        'label' => '退款统计(5%)',
                        'url' => ['/finance-refund/'],
                        'icon' => 'fa fa-angle-right',
                        ]
                        ]
							
                    ],
                    [
                        'label' => '赔偿管理(5%)',
                        'url' => ['/finance-compensate/'],
                        'icon' => 'fa fa-angle-right',
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
// 							'options' => [
// 							'class' => 'treeview',
// 							],
// 							'items' => [
							
// 							]
							
//                     ]
                ],
            ],

            [
                'label' => '供应商管理',
                'url' => ['#'],
                'icon' => 'fa fa-ambulance',
                'visible' => (Yii::$app->user->identity->username == 'admin'),
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