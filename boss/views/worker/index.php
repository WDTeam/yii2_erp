<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\bootstrap\NavBar;
use yii\bootstrap\Modal;

use kartik\nav\NavX;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;

use common\models\Shop;
use core\models\worker\Worker;

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
        Html::a('<i class="glyphicon" ></i>全部 ', ['/worker'], ['class' => 'btn '.Worker::getSearchBtnCss(0), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>待验证 '.Worker::CountWorkerStatus(0), ['index?WorkerSearch[worker_auth_status]=0'], ['class' => 'btn '.Worker::getSearchBtnCss(1), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>待试工 '.Worker::CountWorkerStatus(1), ['index?WorkerSearch[worker_auth_status]=1'], ['class' => 'btn '.Worker::getSearchBtnCss(2), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>待上岗 '.Worker::CountWorkerStatus(2), ['index?WorkerSearch[worker_auth_status]=2'], ['class' => 'btn '.Worker::getSearchBtnCss(3), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>全职 '.Worker::CountWorkerRule(1), ['index?WorkerSearch[worker_rule_id]=1'], ['class' => 'btn '.Worker::getSearchBtnCss(4), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>兼职 '.Worker::CountWorkerRule(2), ['index?WorkerSearch[worker_rule_id]=2'], ['class' => 'btn '.Worker::getSearchBtnCss(5), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>时段 '.Worker::CountWorkerRule(3), ['index?WorkerSearch[worker_rule_id]=3'], ['class' => 'btn '.Worker::getSearchBtnCss(6), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>高峰 '.Worker::CountWorkerRule(4), ['index?WorkerSearch[worker_rule_id]=4'], ['class' => 'btn '.Worker::getSearchBtnCss(7), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>请假 '.Worker::CountVacationWorker(), ['index?WorkerSearch[worker_is_vacation]=1'], ['class' => 'btn '.Worker::getSearchBtnCss(8), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>封号 '.Worker::CountBlockWorker(), ['index?WorkerSearch[worker_is_block]=1'], ['class' => 'btn '.Worker::getSearchBtnCss(9), 'style' => 'margin-right:10px']) .
        Html::a('<i class="glyphicon" ></i>黑名单 '.Worker::CountBlackListWorker(), ['index?WorkerSearch[worker_is_blacklist]=1'], ['class' => 'btn '.Worker::getSearchBtnCss(10), 'style' => 'margin-right:10px']);

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

                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['index?getData=1'], [
                        'class' => 'btn btn-default',
                        'title' => Yii::t('kvgrid', 'Reset Grid')
                    ]),
            ],
        'columns' => [
            [
                'class'=>'kartik\grid\CheckboxColumn',
                'headerOptions'=>['class'=>'kartik-sheet-style'],
            ],

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
                'label' => '所属商圈',
                'value' => function($dataProvider){
                    return Worker::getWorkerDistrictShow($dataProvider->id);
                },
                'width' => "8%",
            ],
            [
                'format' => 'raw',
                'label' => '状态',
                'value' => function($dataProvider){
                    return Worker::getWorkerAuthStatusShow($dataProvider->worker_auth_status);
                },
                'width' => "100px",
            ],
            [
                'format' => 'raw',
                'label' => '阿姨入职时间',
                'value' => function ($dataProvider) {
                    return date('Y-m-d H:i', $dataProvider->created_ad);
                },
                'width' => "120px",
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'header' => '操作',
                'width' => "9%",
                'template' =>'{view} {update} {vacation} {block} {delete}',
                'contentOptions'=>[
                    'style'=>'font-size: 12px;padding-right:2px',
                ],
                'viewOptions'=>[
                    'style'=>'margin-right:3px'
                ],
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['worker/view', 'id' => $model->id, 'edit' => 't']), [
                            'title' => Yii::t('yii', '修改'),
                            'style' => 'margin-right:3px'
                        ]);
                    },

                    'vacation' => function ($url, $model) {
                        return Html::a('<span class="fa fa-fw fa-history"></span>',
                            [
                                '/worker/create-vacation',
                                'workerIds' => $model->id
                            ]
                            ,
                            [
                                'title' => Yii::t('yii', '请假信息录入'),
                                'data-toggle' => 'modal',
                                'data-target' => '#vacationModal',
                                'class'=>'vacation',
                                'data-id'=>$model->id,
                                'style' => 'margin-right:3px'
                            ]);
                    },
                ],
            ],
        ],
        'responsive' => true,
        'hover' => true,
        'condensed' => true,
        'floatHeader' => true,
        'striped'=>false,
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '. Html::encode($this->title) . ' </h3>',
            'type' => 'info',
            'before' =>$b,
            'after' => Html::a('<i ></i>批量休假',['/worker/create-vacation?workerIds='],['class' => 'btn btn-success batchVacation','data-target' => '#vacationModal','data-toggle' => 'modal','type'=>1,'style'=>'margin-right:20px'])
                .Html::a('<i></i>批量事假',['/worker/create-vacation?workerIds='],['class' => 'btn btn-success batchVacation','type'=>2,'data-target' => '#vacationModal','data-toggle' => 'modal']),
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
JSCONTENT
        );


    ?>
