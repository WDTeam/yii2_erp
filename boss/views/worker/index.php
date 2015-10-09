<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\models\Shop;
use yii\helpers\ArrayHelper;
use kartik\nav\NavX;
use yii\bootstrap\NavBar;
use yii\bootstrap\Modal;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
$this->title = Yii::t('app', '阿姨管理');
$this->params['breadcrumbs'][] = $this->title;

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

    $b =
        Html::a('<i class="glyphicon" ></i>全部 ', ['/worker'], ['class' => 'btn '.$searchModel->getSearchBtnCss(0), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>待检验 '.$searchModel->AuthStatusCount, ['index?WorkerSearch[worker_auth_status]=0'], ['class' => 'btn '.$searchModel->getSearchBtnCss(1), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>待试工 '.$searchModel->OntrialStatusCount, ['index?WorkerSearch[worker_ontrial_status]=0'], ['class' => 'btn '.$searchModel->getSearchBtnCss(2), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>待上岗 '.$searchModel->OnboardStatusCount, ['index?WorkerSearch[worker_onboard_status]=0'], ['class' => 'btn '.$searchModel->getSearchBtnCss(3), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>全职 '.$searchModel->QCount, ['index?WorkerSearch[worker_rule_id]=1'], ['class' => 'btn '.$searchModel->getSearchBtnCss(4), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>兼职 '.$searchModel->JCount, ['index?WorkerSearch[worker_rule_id]=2'], ['class' => 'btn '.$searchModel->getSearchBtnCss(5), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>时段 '.$searchModel->SCount, ['index?WorkerSearch[worker_rule_id]=3'], ['class' => 'btn '.$searchModel->getSearchBtnCss(6), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>高峰 '.$searchModel->GCount, ['index?WorkerSearch[worker_rule_id]=4'], ['class' => 'btn '.$searchModel->getSearchBtnCss(7), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>请假 '.$searchModel->VacationCount, ['index?WorkerSearch[worker_is_vacation]=1'], ['class' => 'btn '.$searchModel->getSearchBtnCss(8), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>封号 '.$searchModel->BlockCount, ['index?WorkerSearch[worker_is_block]=1'], ['class' => 'btn '.$searchModel->getSearchBtnCss(9), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>黑名单 '.$searchModel->BlackListCount, ['index?WorkerSearch[worker_is_blacklist]=1'], ['class' => 'btn '.$searchModel->getSearchBtnCss(10), 'style' => 'margin-right:10px']);

    ?>
    <?php
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'export'=>false,
        'toolbar' =>
            [
                'content'=>

                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['index?getData=1'], [
                        'class' => 'btn btn-default',
                        'title' => Yii::t('kvgrid', 'Reset Grid')
                    ]),
            ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'worker_name',
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
            [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view} {update} {vacation} {block} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['worker/view', 'id' => $model->id, 'edit' => 't']), [
                            'title' => Yii::t('yii', '修改'),
                        ]);
                    },
                    'vacation' => function ($url, $model) {
                        return Html::a('<span class="fa fa-fw fa-history"></span>',
                            [
                                '/worker/vacation-create',
                                'workerId' => $model->id
                            ]
                            ,
                            [
                                'title' => Yii::t('yii', '请假信息录入'),
                                'data-toggle' => 'modal',
                                'data-target' => '#vacationModal',
                                'class'=>'vacation',
                                'data-id'=>$model->id,
                            ]);
                    },
                    'block' => function ($url, $model) {
                        return Html::a('<span class="fa fa-fw fa-lock"></span>',
                        [
                            '/worker/block-create',
                            'workerId' => $model->id
                        ]
                        ,
                        [
                            'title' => Yii::t('yii', '封号信息录入'),
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
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '. Html::encode($this->title) . ' </h3>',
            'type' => 'info',
            'before' =>
                  $b,
             'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List',
                ['index'],
                ['class' => 'btn btn-info']),
            'showFooter' => false
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
JSCONTENT
        );



    ?>
