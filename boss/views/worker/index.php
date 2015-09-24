<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\models\Shop;
use yii\helpers\ArrayHelper;
use kartik\nav\NavX;
use yii\bootstrap\NavBar;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
$this->title = Yii::t('app', '阿姨管理');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="worker-index">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //echo Html::a(Yii::t('app', 'Create {modelClass}', ['modelClass' => 'Worker',]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php
//    $navBarOptions = [
//        'options'=>['class'=>'navbar-nav'],
//    ];
    //NavBar::begin($navBarOptions);
     NavX::widget([
        'options'=>['class'=>'navbar-nav'],
        'items' => [
            ['label' => '身份', 'active'=>true, 'items' => [
                ['label' => '兼职', 'url' => '#'],
                ['label' => '全职', 'url' => '#'],
                ['label' => '高峰', 'url' => '#'],
                ['label' => '时段', 'url' => '#'],
                //'<li class="divider"></li>',
            ]],
            ['label' => '待试验', 'url' => '/worker/index?WorkerSearch[worker_auth_status]=0'],
            ['label' => '未培训', 'url' => '/worker/index?WorkerSearch[worker_ontrial_status]=0'],
            ['label' => '试工', 'url' => '/worker/index?WorkerSearch[worker_onboard_status]=0'],
            ['label' => '请假', 'url' => '#'],
            ['label' => '封号', 'url' => '#'],
            ['label' => '黑名单', 'url' => '#'],
            //'<li class="divider"></li>',
        ]
    ]);
    //NavBar::end();
    ?>
    <?php Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'worker_name',
            [
                'format' => 'raw',
                'label' => '门店名称',
                'value' => function ($dataProvider) {
                    //return Shop::findOne($dataProvider->shop_id)->name;
                }
            ],
            'worker_phone',
            'worker_idcard',
//            'worker_password',
//            'worker_photo',
//            'worker_level',
//            'worker_auth_status',
//            'worker_ontrial_status',
//            'worker_onboard_status',
//            'worker_work_city',
//            'worker_work_area',
//            'worker_work_street',
//            'worker_work_lng',
//            'worker_work_lat',
            [
                'format' => 'raw',
                'label' => '阿姨类型',
                'value' => function ($dataProvider) {
                    return $dataProvider->worker_type ? '自有' : '非自有';
                },
                'width' => "100px",
            ],
            [
                'format' => 'raw',
                'label' => '阿姨录入时间',
                'value' => function ($dataProvider) {
                    return date('Y-m-d H:i', $dataProvider->created_ad);
                },
                'width' => "120px",
            ],
//            'updated_ad',
//            'isdel',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['worker/view', 'id' => $model->id, 'edit' => 't']), [
                            'title' => Yii::t('yii', 'Edit'),
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
            'before' =>
                  //$a,
                Html::a('<i class="glyphicon" ></i>待试验 '.$searchModel->AuthStatusCount, ['index?WorkerSearch[worker_auth_status]=0'], ['class' => 'btn btn-success ', 'style' => 'margin-right:10px']) .
                Html::a('<i class="glyphicon" ></i>待试工 '.$searchModel->OntrialStatusCount, ['index?WorkerSearch[worker_ontrial_status]=0'], ['class' => 'btn btn-success', 'style' => 'margin-right:10px']) .
                Html::a('<i class="glyphicon" ></i>待上岗 '.$searchModel->OnboardStatusCount, ['index?WorkerSearch[worker_onboard_status]=0'], ['class' => 'btn btn-success', 'style' => 'margin-right:10px']) .
                Html::a('<i class="glyphicon" ></i>全职 '.$searchModel->QCount, ['index?WorkerSearch[worker_rule_id]=1'], ['class' => 'btn btn-success', 'style' => 'margin-right:10px']) .
                Html::a('<i class="glyphicon" ></i>兼职 '.$searchModel->JCount, ['index?WorkerSearch[worker_rule_id]=2'], ['class' => 'btn btn-success', 'style' => 'margin-right:10px']) .
                Html::a('<i class="glyphicon" ></i>时段 '.$searchModel->SCount, ['index?WorkerSearch[worker_rule_id]=3'], ['class' => 'btn btn-success', 'style' => 'margin-right:10px']) .
                Html::a('<i class="glyphicon" ></i>高峰 '.$searchModel->GCount, ['index?WorkerSearch[worker_rule_id]=4'], ['class' => 'btn btn-success', 'style' => 'margin-right:10px']) .
                Html::a('<i class="glyphicon" ></i>请假 '.$searchModel->VacationCount, ['index?WorkerSearch[worker_rule_id]=1'], ['class' => 'btn btn-success', 'style' => 'margin-right:10px']) .
                Html::a('<i class="glyphicon" ></i>封号 '.$searchModel->BlockCount, ['index?WorkerSearch[worker_is_block]=1'], ['class' => 'btn btn-success', 'style' => 'margin-right:10px']) .
                Html::a('<i class="glyphicon" ></i>黑名单 '.$searchModel->BlackListCount, ['index?WorkerSearch[worker_is_blacklist]=1'], ['class' => 'btn btn-success', 'style' => 'margin-right:10px']),
            'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List',
                ['index'],
                ['class' => 'btn btn-info']),
            'showFooter' => false
        ],
    ]);
    Pjax::end(); ?>

</div>
