<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\models\Worker;
use yii\bootstrap\Modal;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
$this->title = Yii::t('app', '阿姨管理');
$this->params['breadcrumbs'][] = $this->title;
$workerVacationModel = new \common\models\WorkerVacation();
$workerModel = new \common\models\Worker();
?>

<div style="height:1000px">
    <?php
//    Modal::begin([
//        'header' => '<h4 class="modal-title">操作请假信息</h4>',
//        'toggleButton' => ['label' => '<i class="fa fa-fw fa-lock"></i>操作请假信息', 'class' => 'btn btn-primary']
//    ]);
//    echo $this->render('create_vacation',['workerModel'=>$workerModel,'workerVacationModel'=>$workerVacationModel]);
//    Modal::end();
    ?>
    <?php
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $workerVacationData,
        'columns' => [
            [
                'attribute'=>'worker_id',
                'label'=>'阿姨姓名',
                'value'=>function($dataProvider){
                    return Worker::findOne(['id'=>$dataProvider->worker_id])->worker_name;
                },
            ],
            [
                'attribute'=>'worker_vacation_start_time',
                'format' => ['date', 'php:Y-m-d'],
                'label' => '请假开始时间',
            ],
            [
                'attribute'=>'worker_vacation_finish_time',
                'format' => ['date', 'php:Y-m-d'],
                'label' => '请假结束时间',
            ],
            [
                'attribute'=>'worker_vacation_type',
                'format' => 'raw',
                'label' => '请假类型',
                'value' => function ($dataProvider) {
                    return $dataProvider->worker_vacation_type==1?'休假':'事假';
                },
            ],
            [
                'attribute'=>'worker_vacation_extend',
                'format' => 'raw',
                'label' => '请假备注',
            ],
        ],
//        'responsive' => true,
//        'hover' => true,
//        'condensed' => true,
//        'floatHeader' => true,
//        'panel' => [
//            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '. Html::encode($this->title) . ' </h3>',
//            'type' => 'info',
//
//            'after' => '',
//            'showFooter' => true
//        ],
        'summary'=>false
    ]);
    Pjax::end();
    ?>
    </div>