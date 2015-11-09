<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\bootstrap\NavBar;
use yii\bootstrap\Modal;

use kartik\nav\NavX;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;

use core\models\shop\Shop;
use boss\models\worker\Worker;
use boss\models\worker\WorkerVacation;
use boss\models\worker\WorkerVacationApplication;
use boss\models\worker\WorkerIdentityConfig;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
$this->title = Yii::t('app', '阿姨管理');
$this->params['breadcrumbs'][1] = $this->title;
$params = Yii::$app->request->getQueryParams();
if(\Yii::$app->user->identity->isMiniBossUser()){
    $columns =[
        [
            'class'=>'kartik\grid\CheckboxColumn',
            'headerOptions'=>['class'=>'kartik-sheet-style'],
        ],
        [
            'format' => 'raw',
            'label' => '阿姨姓名',
            'value' => function ($dataProvider) {
                if($dataProvider->id){
                    return Html::a($dataProvider->worker_name, Yii::$app->urlManager->createUrl(['worker/worker/view', 'id' => $dataProvider->id]), [
                        'title' => '查看',
                        'style' => 'margin-right:5%',
                        'data-pjax'=>0,
                        'target' => '_blank',
                    ]);
                }
            }
        ],

        'worker_phone',
        'worker_idcard',
        [
            'format' => 'raw',
            'label' => '阿姨类型',
            'value' => function ($dataProvider) {
                return Worker::getWorkerTypeShow($dataProvider->worker_type);
            },
            'width' => "100px",
        ],
        [
            'format' => 'raw',
            'label' => '阿姨身份',
            'value' => function ($dataProvider) {
                if($dataProvider->id){
                    return WorkerIdentityConfig::getWorkerIdentityShow($dataProvider->worker_identity_id);
                }
            },
            'width' => "70px",
        ],
        [
            'format' => 'raw',
            'label' => '所属商圈',
            'value' => function($dataProvider){
                return Worker::getWorkerDistrictShow($dataProvider->id);
            },
            'width' => "8%",
        ],
        /******* 选中其他状态显示列 ********/
        [
            'format' => 'raw',
            'label' => '状态',
            'hidden' => Worker::columnsIsHidden('other'),
            'value' => function($dataProvider){
                return Worker::getWorkerAuthStatusShow($dataProvider->worker_auth_status);
            },
            'width' => "100px",
        ],
        [
            'format' => 'raw',
            'label' => '阿姨入职时间',
            'hidden' => Worker::columnsIsHidden('other'),
            'value' => function ($dataProvider) {
                return date('Y-m-d H:i', $dataProvider->created_ad);
            },
            'width' => "120px",
        ],
        /******* 选中其他状态显示列 ********/
        /****** 选中黑名单显示列 ******/
        [
            'format' => 'raw',
            'hidden' => Worker::columnsIsHidden('blacklist'),
            'label' => '状态',
            'value' => function ($dataProvider) {
                return '黑名单';
            },
            'width' => "120px",
        ],
        [
            'format' => 'raw',
            'hidden' => Worker::columnsIsHidden('blacklist'),
            'label' => '列入黑名单时间',
            'value' => function ($dataProvider) {
                return date('Y-m-d H:i', $dataProvider->worker_blacklist_time);
            },
            'width' => "120px",
        ],
        [
            'format' => 'raw',
            'hidden' => Worker::columnsIsHidden('blacklist'),
            'label' => '黑名单原因',
            'value' => function ($dataProvider) {
                return $dataProvider->worker_blacklist_reason;
            },
            'width' => "120px",
        ],
        /****** 选中黑名单显示列 ******/
        /****** 选中离职显示列 ******/
        [
            'format' => 'raw',
            'hidden' => Worker::columnsIsHidden('dimission'),
            'label' => '状态',
            'value' => function ($dataProvider) {
                return '离职';
            },
            'width' => "120px",
        ],
        [
            'format' => 'raw',
            'hidden' => Worker::columnsIsHidden('dimission'),
            'label' => '离职时间',
            'value' => function ($dataProvider) {
                return date('Y-m-d H:i', $dataProvider->worker_dimission_time);
            },
            'width' => "120px",
        ],
        [
            'format' => 'raw',
            'hidden' => Worker::columnsIsHidden('dimission'),
            'label' => '离职原因',
            'value' => function ($dataProvider) {
                return $dataProvider->worker_dimission_reason;
            },
            'width' => "120px",
        ],
        /****** 选中离职显示列 ******/
        [
            'format' => 'raw',
            'label' => '订单总数',
            'value' => function ($dataProvider) {
                return Html::a($dataProvider->workerStatRelation->worker_stat_order_num, Yii::$app->urlManager->createUrl(['order/order/?OrderSearch[order_worker_phone]='.$dataProvider->worker_phone, 'id' => $dataProvider->id]), [
                    'title' =>'阿姨订单',
                    'style' => 'margin-right:5%',
                    'data-pjax'=>'0',
                    'target' => '_blank',
                ]);

            },
            'width' => "120px",
        ],
        [
            'format' => 'raw',
            'label' => '投诉数',
            'value' => function ($dataProvider) {
                return Html::a($dataProvider->workerStatRelation->worker_stat_order_complaint, Yii::$app->urlManager->createUrl(['order/order-complaint/index/','OrderComplaintSearch[order_worker_phone]'=>$dataProvider->worker_phone]), [
                    'title' =>'阿姨投诉',
                    'style' => 'margin-right:5%',
                    'data-pjax'=>'0',
                    'target' => '_blank',
                ]);
            },
            'width' => "120px",
        ],
        [
            'format' => 'raw',
            'label' => '好评数',
            'value' => function ($dataProvider) {
                return Html::a($dataProvider->workerStatRelation->worker_stat_order_complaint, Yii::$app->urlManager->createUrl(['customer/customer-comment/','CustomerCommentSearch[customer_comment_level]'=>1, 'CustomerCommentSearch[worker_id]' => $dataProvider->id]), [
                    'title' =>'阿姨好评',
                    'style' => 'margin-right:5%',
                    'data-pjax'=>'0',
                    'target' => '_blank',
                ]);
            },
            'width' => "120px",
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'header' => '操作',
            'width' => "20%",
            'template' =>'{order}{view} {auth} {vacation} {block} {delete}',
            'contentOptions'=>[
                'style'=>'font-size: 12px;padding-right:2px',
            ],

            'buttons' => [
                'order' => function ($url, $model) {
                    return Html::a('<span class="btn btn-primary">订单</span>', Yii::$app->urlManager->createUrl(['order/order/?OrderSearch[order_worker_phone]='.$model->worker_phone, 'id' => $model->id]), [
                        'title' =>'订单',
                        'style' => 'margin-right:5%',
                        'data-pjax'=>'0',
                        'target' => '_blank',
                    ]);
                },
                'view' => function ($url, $model) {
                    return Html::a('<span class="btn btn-primary">查看</span>', Yii::$app->urlManager->createUrl(['worker/worker/view', 'id' => $model->id]), [
                        'title' =>'查看',
                        'style' => 'margin-right:5%'
                    ]);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<span class="btn btn-primary">删除</span>', Yii::$app->urlManager->createUrl(['worker/worker/delete', 'id' => $model->id]), [
                        'title' =>'删除',
                        'style' => 'margin-right:5%'
                    ]);
                },
                'auth' => function ($url, $model) {
                    return Html::a('<span class="btn btn-primary">管理</span>', Yii::$app->urlManager->createUrl(['worker/worker/auth', 'id' => $model->id]), [
                        'title' =>'管理',
                        'style' => 'margin-right:5%'
                    ]);
                },
//                    'vacation' => function ($url, $model) {
//                        return Html::a('<span class="fa fa-fw fa-history"></span>',
//                            [
//                                '/worker/create-vacation',
//                                'workerIds' => $model->id
//                            ]
//                            ,
//                            [
//                                'title' => Yii::t('yii', '请假信息录入'),
//                                'data-toggle' => 'modal',
//                                'data-target' => '#vacationModal',
//                                'class'=>'vacation',
//                                'data-id'=>$model->id,
//                                'style' => 'margin-right:3px'
//                            ]);
//                    },
            ],
        ]
    ];
}elseif(isset($params['WorkerSearch']['worker_vacation_application_approve_status'])){
    $vacationBtn = '';
    $columns =[
        [
            'class'=>'kartik\grid\CheckboxColumn',
            'headerOptions'=>['class'=>'kartik-sheet-style'],
        ],
        [
            'format' => 'raw',
            'label' => '阿姨姓名',
            'value' => function ($dataProvider) {
                if($dataProvider->id) {
                    return Html::a('<span class="btn btn-primary">'.$dataProvider->worker->worker_name.'</span>', Yii::$app->urlManager->createUrl(['worker/worker/view', 'id' => $dataProvider->worker->id]), [
                        'title' => '查看',
                        'style' => 'margin-right:5%'
                    ]);
                }
//                if($dataProvider->id){
//                    return '<a href="" pjax-data=0'>$dataProvider->worker->worker_name.'</a>';
//                }
            },
        ],
        [
            'format' => 'raw',
            'label' => '门店名称',
            'value' => function ($dataProvider) {
                if($dataProvider->id){
                    if($dataProvider->worker->shop_id && Shop::findOne($dataProvider->worker->shop_id)){
                        return Shop::findOne($dataProvider->worker->shop_id)->name;
                    }
                }
            }
        ],
        [
            'format' => 'raw',
            'label'=>'阿姨手机',
            'value' => function ($dataProvider) {
                if($dataProvider->id) {
                    return $dataProvider->worker->worker_phone;
                }
            },
            'width' => "7%",
        ],
        [
            'format' => 'raw',
            'label'=>'阿姨身份证号',
            'value' => function ($dataProvider) {
                if($dataProvider->id){
                    return $dataProvider->worker->worker_idcard;
                }
            },
            'width' => "10%",
        ],
        [
            'format' => 'raw',
            'label' => '阿姨类型',
            'value' => function ($dataProvider) {
                if($dataProvider->id){
                    return Worker::getWorkerTypeShow($dataProvider->worker->worker_type);
                }
            },
            'width' => "70px",
        ],
        [
            'format' => 'raw',
            'label' => '阿姨身份',
            'value' => function ($dataProvider) {
                if($dataProvider->id){
                    return WorkerIdentityConfig::getWorkerIdentityShow($dataProvider->worker->worker_identity_id);
                }
            },
            'width' => "70px",
        ],
        [
            'format' => 'raw',
            'label' => '所属商圈',
            'value' => function($dataProvider){
                if($dataProvider->id){
                    return Worker::getWorkerDistrictShow($dataProvider->worker->id);
                }
            },
            'width' => "8%",
        ],
        [
            'format' => 'raw',
            'label' => '请假开始时间',
            'value' => function($dataProvider){
                return date('Y-m-d',$dataProvider->worker_vacation_application_start_time);
            },
            'width' => "8%",
        ],
        [
            'format' => 'raw',
            'label' => '请假结束时间',
            'value' => function($dataProvider){
                return date('Y-m-d',$dataProvider->worker_vacation_application_end_time);
            },
            'width' => "8%",
        ],
        [
            'format' => 'raw',
            'label' => '请假类型',
            'value' => function($dataProvider){
                return WorkerVacation::getWorkerVacationTypeShow($dataProvider->worker_vacation_application_type);
            },
            'width' => "8%",
        ],
        [
            'format' => 'raw',
            'label' => '审核状态',
            'value' => function(){
                return '未审核';
            },
            'width' => "8%",
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'header' => '操作',
            'width' => "20%",
            'template' =>'{operate_application_success}{operate_application_failed}{view}{auth}',
            'contentOptions'=>[
                'style'=>'font-size: 12px;padding-right:2px',
            ],
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span class="btn btn-primary">查看</span>', Yii::$app->urlManager->createUrl(['worker/worker/view', 'id' => $model->worker->id]), [
                        'title' =>'查看',
                        'style' => 'margin-right:5%'
                    ]);
                },
                'auth' => function ($url, $model) {
                    return Html::a('<span class="btn btn-primary">管理</span>', Yii::$app->urlManager->createUrl(['worker/worker/auth', 'id' => $model->worker->id]), [
                        'title' =>'管理',
                        'style' => 'margin-right:5%'
                    ]);
                },
                'operate_application_success' => function ($url, $model) {
                    return Html::a('<span class="btn btn-primary" onclick="return confirm(\'确认通过申请?\')">通过</span>', Yii::$app->urlManager->createUrl(['worker/worker/operate-vacation-application', 'id' => $model->id,'status'=>1]), [
                        'title' =>'通过',
                        'style' => 'margin-right:5%'
                    ]);
                },
                'operate_application_failed' => function ($url, $model) {
                    return Html::a('<span class="btn btn-primary" onclick="return confirm(\'确认拒绝申请?\')" >拒绝</span>', Yii::$app->urlManager->createUrl(['worker/worker/operate-vacation-application', 'id' => $model->id,'status'=>2]), [
                        'title' =>'拒绝',
                        'style' => 'margin-right:5%'
                    ]);
                },
            ],
        ],
    ];
}else{
    $vacationBtn =
        Html::a('<i ></i>批量休假',['create-vacation?workerIds='],['class' => ' btn btn-success batchVacation','data-target' => '#vacationModal','data-toggle' => 'modal','type'=>1,'style'=>'margin-right:20px'])
        .Html::a('<i></i>批量事假',['create-vacation?workerIds='],['class' => 'btn btn-success batchVacation','type'=>2,'data-target' => '#vacationModal','data-toggle' => 'modal']);
    $columns =[
        [
            'class'=>'kartik\grid\CheckboxColumn',
            'headerOptions'=>['class'=>'kartik-sheet-style'],
        ],
        [
            'format' => 'raw',
            'label' => '阿姨姓名',
            'value' => function ($dataProvider) {
                if($dataProvider->id){
                    return Html::a($dataProvider->worker_name, Yii::$app->urlManager->createUrl(['worker/worker/view', 'id' => $dataProvider->id]), [
                        'title' => '查看',
                        'style' => 'margin-right:5%',
                        'data-pjax'=>0,
                        'target' => '_blank',
                    ]);
                }
            }
        ],
        [
            'format' => 'raw',
            'label' => '门店名称',
            'value' => function ($dataProvider) {
                if($dataProvider->shop_id && Shop::findOne($dataProvider->shop_id)){
                    return Shop::findOne($dataProvider->shop_id)->name;
                }
            }
        ],
        'worker_phone',
        'worker_idcard',
        [
            'format' => 'raw',
            'label' => '阿姨类型',
            'value' => function ($dataProvider) {
                return Worker::getWorkerTypeShow($dataProvider->worker_type);
            },
            'width' => "100px",
        ],
        [
            'format' => 'raw',
            'label' => '阿姨身份',
            'value' => function ($dataProvider) {
                if($dataProvider->id){
                    return WorkerIdentityConfig::getWorkerIdentityShow($dataProvider->worker_identity_id);
                }
            },
            'width' => "70px",
        ],
        [
            'format' => 'raw',
            'label' => '所属商圈',
            'value' => function($dataProvider){
                return Worker::getWorkerDistrictShow($dataProvider->id);
            },
            'width' => "8%",
        ],
        /******* 选中其他状态显示列 ********/
        [
            'format' => 'raw',
            'label' => '状态',
            'hidden' => Worker::columnsIsHidden('other'),
            'value' => function($dataProvider){
                return Worker::getWorkerAuthStatusShow($dataProvider->worker_auth_status);
            },
            'width' => "100px",
        ],
        [
            'format' => 'raw',
            'label' => '阿姨入职时间',
            'hidden' => Worker::columnsIsHidden('other'),
            'value' => function ($dataProvider) {
                return date('Y-m-d H:i', $dataProvider->created_ad);
            },
            'width' => "120px",
        ],
        /******* 选中其他状态显示列 ********/
        /****** 选中黑名单显示列 ******/
        [
            'format' => 'raw',
            'hidden' => Worker::columnsIsHidden('blacklist'),
            'label' => '状态',
            'value' => function ($dataProvider) {
                return '黑名单';
            },
            'width' => "120px",
        ],
        [
            'format' => 'raw',
            'hidden' => Worker::columnsIsHidden('blacklist'),
            'label' => '列入黑名单时间',
            'value' => function ($dataProvider) {
                return date('Y-m-d H:i', $dataProvider->worker_blacklist_time);
            },
            'width' => "120px",
        ],
        [
            'format' => 'raw',
            'hidden' => Worker::columnsIsHidden('blacklist'),
            'label' => '黑名单原因',
            'value' => function ($dataProvider) {
                return $dataProvider->worker_blacklist_reason;
            },
            'width' => "120px",
        ],
        /****** 选中黑名单显示列 ******/
        /****** 选中离职显示列 ******/
        [
            'format' => 'raw',
            'hidden' => Worker::columnsIsHidden('dimission'),
            'label' => '状态',
            'value' => function ($dataProvider) {
                return '离职';
            },
            'width' => "120px",
        ],
        [
            'format' => 'raw',
            'hidden' => Worker::columnsIsHidden('dimission'),
            'label' => '离职时间',
            'value' => function ($dataProvider) {
                return date('Y-m-d H:i', $dataProvider->worker_dimission_time);
            },
            'width' => "120px",
        ],
        [
            'format' => 'raw',
            'hidden' => Worker::columnsIsHidden('dimission'),
            'label' => '离职原因',
            'value' => function ($dataProvider) {
                return $dataProvider->worker_dimission_reason;
            },
            'width' => "120px",
        ],
        /****** 选中离职显示列 ******/
        [
            'class' => 'kartik\grid\ActionColumn',
            'header' => '操作',
            'width' => "20%",
            'template' =>'{order}{view} {auth} {vacation} {block} {delete}',
            'contentOptions'=>[
                'style'=>'font-size: 12px;padding-right:2px',
            ],

            'buttons' => [
                'order' => function ($url, $model) {
                    return Html::a('<span class="btn btn-primary">订单</span>', Yii::$app->urlManager->createUrl(['order/order/?OrderSearch[order_worker_phone]='.$model->worker_phone, 'id' => $model->id]), [
                        'title' =>'订单',
                        'style' => 'margin-right:5%',
                        'data-pjax'=>'0',
                        'target' => '_blank',
                    ]);
                },
                'view' => function ($url, $model) {
                    return Html::a('<span class="btn btn-primary">查看</span>', Yii::$app->urlManager->createUrl(['worker/worker/view', 'id' => $model->id]), [
                        'title' =>'查看',
                        'style' => 'margin-right:5%'
                    ]);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<span class="btn btn-primary">删除</span>', Yii::$app->urlManager->createUrl(['worker/worker/delete', 'id' => $model->id]), [
                        'title' =>'删除',
                        'style' => 'margin-right:5%'
                    ]);
                },
                'auth' => function ($url, $model) {
                    return Html::a('<span class="btn btn-primary">管理</span>', Yii::$app->urlManager->createUrl(['worker/worker/auth', 'id' => $model->id]), [
                        'title' =>'管理',
                        'style' => 'margin-right:5%'
                    ]);
                },
//                    'vacation' => function ($url, $model) {
//                        return Html::a('<span class="fa fa-fw fa-history"></span>',
//                            [
//                                '/worker/create-vacation',
//                                'workerIds' => $model->id
//                            ]
//                            ,
//                            [
//                                'title' => Yii::t('yii', '请假信息录入'),
//                                'data-toggle' => 'modal',
//                                'data-target' => '#vacationModal',
//                                'class'=>'vacation',
//                                'data-id'=>$model->id,
//                                'style' => 'margin-right:3px'
//                            ]);
//                    },
            ],
        ]
    ];
}

?>
<div class="worker-index">
    <div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 阿姨搜索</h3>
    </div>

    <div class="panel-body">
        <?php

        //Modal::begin(['header' => '<h2>Hello world</h2>','toggleButton' =>['label'=>'Search','class' => 'btn btn-success','id'=>'myModal']]);
        echo $this->render('_search', ['model' => $searchModel]);
        //Modal::end();
        ?>

    </div>
    </div>
    <p>
        <?php //echo Html::a(Yii::t('app', 'Create {modelClass}', ['modelClass' => 'Worker',]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php

    $switchBtn =
        Html::a('<i class="glyphicon" ></i>全部 '.Worker::CountWorker(), ['/worker/worker'], ['class' => 'btn '.Worker::setBtnCss(0), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>待审核 '.Worker::CountWorkerStatus(0), ['index?WorkerSearch[worker_auth_status]=0'], ['class' => 'btn '.Worker::setBtnCss(1), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>待试工 '.Worker::CountWorkerStatus(2), ['index?WorkerSearch[worker_auth_status]=2'], ['class' => 'btn '.Worker::setBtnCss(2), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>待上岗 '.Worker::CountWorkerStatus(3), ['index?WorkerSearch[worker_auth_status]=3'], ['class' => 'btn '.Worker::setBtnCss(3), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>全职 '.Worker::CountWorkerIdentity(1), ['index?WorkerSearch[worker_identity_id]=1'], ['class' => 'btn '.Worker::setBtnCss(4), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>兼职 '.Worker::CountWorkerIdentity(2), ['index?WorkerSearch[worker_identity_id]=2'], ['class' => 'btn '.Worker::setBtnCss(5), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>时段 '.Worker::CountWorkerIdentity(3), ['index?WorkerSearch[worker_identity_id]=3'], ['class' => 'btn '.Worker::setBtnCss(6), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>高峰 '.Worker::CountWorkerIdentity(4), ['index?WorkerSearch[worker_identity_id]=4'], ['class' => 'btn '.Worker::setBtnCss(7), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>请假 '.Worker::CountVacationWorker(), ['index?WorkerSearch[worker_is_vacation]=1'], ['class' => 'btn '.Worker::setBtnCss(8), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>封号 '.Worker::CountBlockWorker(), ['index?WorkerSearch[worker_is_block]=1'], ['class' => 'btn '.Worker::setBtnCss(9), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>黑名单 '.Worker::CountBlackListWorker(), ['index?WorkerSearch[worker_is_blacklist]=1'], ['class' => 'btn '.Worker::setBtnCss(10), 'style' => 'margin-right:10px']).
        Html::a('<i class="glyphicon" ></i>离职 '.Worker::CountDimissionWorker(), ['index?WorkerSearch[worker_is_dimission]=1'], ['class' => 'btn '.Worker::setBtnCss(11), 'style' => 'margin-right:10px']).
        Html::a('<i class="glyphicon" ></i>请假待审核 '.WorkerVacationApplication::CountApplication(), ['index?WorkerSearch[worker_vacation_application_approve_status]=0'], ['class' => 'btn '.Worker::setBtnCss(12), 'style' => 'margin-right:10px']);
    $requestParam = \Yii::$app->request->getQueryParams();

    $vacationBtn =
         Html::a('<i ></i>批量休假',['create-vacation?workerIds='],['class' => ' btn btn-success batchVacation','data-target' => '#vacationModal','data-toggle' => 'modal','type'=>1,'style'=>'margin-right:20px'])
        .Html::a('<i></i>批量事假',['create-vacation?workerIds='],['class' => 'btn btn-success batchVacation','type'=>2,'data-target' => '#vacationModal','data-toggle' => 'modal']);
//        $vacationBtn =
//            Html::a('<i ></i>批量休假',['create-vacation?workerIds='],['disabled'=>'disabled','style'=>'margin-right:20px'])
//            .Html::a('<i></i>批量事假',['create-vacation?workerIds='],['disabled'=>'disabled']);

    ?>
    <?php
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'export'=>false,
        'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
        'headerRowOptions'=>['class'=>'kartik-sheet-style'],
        'filterRowOptions'=>['class'=>'kartik-sheet-style'],
        //'persistResize'=>true,
        'toolbar' =>
            [
                'content'=>
                    Html::a('录入新阿姨', ['create'], [
                        'class' => 'btn btn-default',
                        'title' => Yii::t('kvgrid', 'Reset Grid')
                    ]),
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['index?getData=1'], [
                        'class' => 'btn btn-default',
                        'title' => Yii::t('kvgrid', 'Reset Grid')
                    ]),
            ],
        'columns' => $columns,
        'responsive' => true,
        'hover' => true,
        'condensed' => true,
        'floatHeader' => true,
        'striped'=>false,
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '. Html::encode($this->title) . ' </h3>',
            'type' => 'info',
            'before' =>$switchBtn,
            'after' =>$vacationBtn
        ],
    ]);
    Pjax::end();
    //Modal::begin(['header' => '<h2>Hello world</h2>','toggleButton' =>['id'=>'vacationModal',]]);
        //echo $this->render('_search', ['model' => $searchModel]);
    //Modal::end();
    echo Modal::widget([
        'header' => '<h4 class="modal-title">请假信息录入</h4>',
        'id'=>'vacationModal',
   ]);
    echo Modal::widget([
        'header' => '<h4 class="modal-title">封号信息录入</h4>',
        'id'=>'blockModal',
    ]);
$this->registerJs(<<<JSCONTENT
        $('.vacation').click(function() {
            $('#vacationModal .modal-body').html('加载中……');
            $('#vacationModal .modal-body').eq(0).load(this.href);
        });
        $('.block').click(function() {
            $('#blockModal .modal-body').html('加载中……');
            $('#blockModal .modal-body').eq(0).load(this.href);
        });
        $('.batchVacation').click(function() {
            $('#vacationModal .modal-body').html('加载中……');
            url = this.href;
            vacationType = $(this).attr('type');
            selectWorkerIds = '';
            $('.danger').each(function(index,ele){
                 workerId = $(this).attr('data-key');
                 selectWorkerIds += workerId+','
            })
            if(selectWorkerIds){
                selectWorkerIds = selectWorkerIds.substring(0,selectWorkerIds.length-1);
                url = url.substring(0,url.indexOf("=")+1)+selectWorkerIds+'&vacationType='+vacationType
            }else{
                alert('请选择阿姨');
                return false;
            }
            $('#vacationModal .modal-body').eq(0).load(url);
        });
        function outlinks() {
           if (!document.getElementsByTagName) return;
           var anchors = document.getElementsByTagName("a");
           for (var i=0; i<anchors.length; i++) {
             var anchor = anchors[i];
             if (anchor.getAttribute("href") && anchor.getAttribute("rel") == "external")
             anchor.target = "_blank";
           }
        }
JSCONTENT
        );


    ?>
