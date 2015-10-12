<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\models\Shop;
use yii\helpers\ArrayHelper;
use kartik\nav\NavX;
use yii\bootstrap\NavBar;
use yii\bootstrap\Modal;

use common\models\CustomerPlatform;
use common\models\CustomerChannal;
use common\models\CustomerAddress;

use common\models\GeneralRegion;
use common\models\OrderExtCustomer;
use common\models\CustomerExtBalance;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
$this->title = Yii::t('app', '客户管理');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worker-index">
    <div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 客户搜索</h3>
    </div>
    <div class="panel-body">
        <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    </div>
    <p>
        <?php //echo Html::a(Yii::t('app', 'Create {modelClass}', ['modelClass' => 'Worker',]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'export'=>false,
        'toolbar' =>
            [
                // 'content'=>

                    // Html::a('<i class="glyphicon glyphicon-plus"></i>', ['index?getData=1'], [
                    //     'class' => 'btn btn-default',
                    //     'title' => Yii::t('kvgrid', 'Reset Grid')
                    // ]),
            ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'format' => 'raw',
                'label' => 'ID',
                'value' => function ($dataProvider) {
                    return '<a href="/customer/' . $dataProvider->id . '">'.$dataProvider->id.'</a>';
                },
                'width' => "80px",
            ],
            [
                'format' => 'raw',
                'label' => '姓名',
                'value' => function ($dataProvider) {
                    return $dataProvider->customer_name;
                },
                'width' => "80px",
            ],
            [
                'format' => 'raw',
                'label' => '电话',
                'value' => function ($dataProvider) {
                    return $dataProvider->customer_phone;
                },
                'width' => "80px",
            ],
            [
                'format' => 'raw',
                'label' => '订单地址',
                'value' => function ($dataProvider) {
                    $address_count = CustomerAddress::find()->where([
                        'customer_id'=>$dataProvider->id,
                        ])->count();
                    $customer_address = CustomerAddress::find()->where([
                        'customer_id'=>$dataProvider->id,
                        'customer_address_status'=>1])->one();
                    
                    if ($customer_address) {
                        $general_region_id = $customer_address->general_region_id;
                        $general_region = GeneralRegion::find()->where([
                        'id'=>$general_region_id,
                        ])->one();
                        if ($address_count <= 0) {
                            return '-';
                        }
                        if ($address_count == 1) {
                            return $general_region->general_region_province_name 
                            . $general_region->general_region_city_name 
                            . $general_region->general_region_area_name;
                        }
                        if ($address_count > 1) {
                            return $general_region->general_region_province_name 
                            . $general_region->general_region_city_name 
                            . $general_region->general_region_area_name
                            . '...';
                        }
                    }else{
                        return '-';
                    }
                    
                },
                'width' => "150px",
            ],
            [
                'format' => 'raw',
                'label' => '身份',
                'value' => function ($dataProvider) {
                    return $dataProvider->customer_is_vip ? '会员' : '非会员';
                },
                'width' => "80px",
            ],
            [
                'format' => 'raw',
                'label' => '平台',
                'value' => function ($dataProvider) {
                    $platform = CustomerPlatform::find()->where(['id'=>$dataProvider->platform_id])->one();
                    return $platform ? $platform->platform_name : '-';
                },
                'width' => "80px",
            ],
            [
                'format' => 'raw',
                'label' => '渠道',
                'value' => function ($dataProvider) {
                    $channal = CustomerChannal::find()->where(['id'=>$dataProvider->channal_id])->one();
                    return $channal ? $channal->channal_name : '-';
                },
                'width' => "80px",
            ],
            [
                'format' => 'raw',
                'label' => '订单',
                'value' => function ($dataProvider) {
                    $order_count = OrderExtCustomer::find()->where(['customer_id'=>$dataProvider->id])->count();
                    return '<a href="/order/index?OrderSearch[customer_id]='. $dataProvider->id .'">'.$order_count.'</a>';
                },
                'width' => "80px",
            ],
            [
                'format' => 'raw',
                'label' => '余额',
                'value' => function ($dataProvider) {
                    $customerExtBalance = CustomerExtBalance::findOne($dataProvider->id);
                    $customer_balance = $customerExtBalance != NULL ? $customerExtBalance->customer_balance : 0;
                    return '￥'.$customer_balance;
                },
                'width' => "80px",
            ],
            [
                'format' => 'raw',
                'label' => '投诉',
                'value' => function ($dataProvider) {
                    return '<a href="/order/index?OrderSearch[customer_id]='. $dataProvider->id .'">' . $dataProvider->customer_complaint_times . '</a>';
                },
                'width' => "80px",
            ],
            [
                'format' => 'datetime',
                'label' => '创建时间',
                'value' => function ($dataProvider) {
                    return $dataProvider->created_at;
                    
                },
                'width' => "80px",
            ],
            // [
            //     'format'=>'raw',
            //     'label'=>'操作',
            //     'value'=>function($dataProvider){
            //         if ($dataProvider->is_del) {
            //             $action_label = '取消黑名单';
            //         }
            //         if (!$dataProvider->is_del) {
            //             $action_label = '加入黑名单';
            //         }
            //         return Html::a('<span class="glyphicon glyphicon-pencil">'.$action_label.'</span>', Yii::$app->urlManager->createUrl(['customer/switch-block', 'id' => $dataProvider->id, 'edit' => 't']), [
            //                 'title' => Yii::t('yii', 'Edit'),
            //             ]);
            //     },
            //     'width' => "100px",
            // ],
            // [
            //     'class' => 'yii\grid\ActionColumn',
            //     'buttons' => [
            //         'update' => function ($url, $model) {
            //             return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['customer/view', 'id' => $model->id, 'edit' => 't']), [
            //                 'title' => Yii::t('yii', 'Edit'),
            //             ]);
            //         }

            //     ],
            // ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view} {update} {delete} {block}',
                'buttons' => [
                    'block' => function ($url, $model) {
                        return Html::a('<span class="fa fa-fw fa-lock"></span>',
                        [
                            '/customer/create-block',
                            'customer_id' => $model->id
                        ]
                        ,
                        [
                            'title' => Yii::t('yii', '客户加入黑名单'),
                            'data-toggle' => 'modal',
                            'data-target' => '#blockModal',
                            'class'=>'block',
                            'data-id'=>$model->id,
                        ]);
                    }
                ],
            ],
        ],
        'responsive' => true,
        'hover' => true,
        'condensed' => true,
        'floatHeader' => true,
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> ' . Html::encode($this->title) . ' </h3>',
            'type' => 'info',
            // 'before' =>Html::a('<i class="glyphicon" ></i>黑名单', ['/customer/block?CustomerSearch[is_del]=1'], ['class' => 'btn btn-success', 'style' => 'margin-right:10px']),
            // 'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List',
            //     ['index'],
            //     ['class' => 'btn btn-info']),
            'showFooter' => false
        ],
    ]);
    Pjax::end(); 
    echo Modal::widget([
        'header' => '<h4 class="modal-title">客户加入黑名单</h4>',
        'id'=>'blockModal',
    ]);
    $this->registerJs(<<<JSCONTENT
        $('.block').click(function() {
            $('#blockModal .modal-body').html('加载中……');
            $('#blockModal .modal-body').eq(0).load(this.href);
        });
JSCONTENT
        );
    ?>

</div>



