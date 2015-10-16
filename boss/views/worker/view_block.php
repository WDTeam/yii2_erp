<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use core\models\worker\Worker;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
use kartik\grid\EditableColumn;
$this->title = Yii::t('app', '阿姨管理');
$this->params['breadcrumbs'][] = $this->title;
?>
    <style>
        .workerblock-0-finishtime-popover{padding-right:0px}
    </style>
    <script>
        $('#workerblock-0-finishtime-targ').click(function(){
            alert(1)
        })
    </script>
    <div style="height:1000px">
    <?php
        echo 1;

    ?>
    <?php
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $workerBlockData,
//        'export'=>false,
//        'toolbar' =>
//            [
//                'content'=>
//
//                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['index?getData=1'], [
//                        'class' => 'btn btn-default',
//                        'title' => Yii::t('kvgrid', 'Reset Grid')
//                    ]),
//            ],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'worker_id',
                'class' => 'kartik\grid\EditableColumn',
                'readonly'=>true,
                'label'=>'阿姨姓名',
                'value'=>function($dataProvider){
                    return Worker::findOne(['id'=>$dataProvider->worker_id])->worker_name;
                },
            ],
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute'=>'worker_block_start_time',
                'readonly'=>function($dataProvider){
                        return true;
                 },
                'format' => 'raw',
                'label' => '封号开始时间',
                'value' => function ($dataProvider) {
                    return date('Y-m-d',$dataProvider->worker_block_start_time);
                },

            ],
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute'=>'finishtime',
                'readonly'=>function($dataProvider){
                    if($dataProvider->worker_block_status==0){
                        return false;
                    }else{
                        return true;
                    }
                },
                'label' => '封号结束时间',
//                'value' => function ($dataProvider) {
//                    return date('Y-m-d',$dataProvider->worker_block_finish_time);
//                },
                'editableOptions' => [
                    'header' => '修改封号结束时间',
                    'asPopover' => true,
                    'inputType' => \kartik\editable\Editable::INPUT_DATE,
                    'options' => [
                        'pluginOptions'=>[
                            'format'=>'yyyy-mm-dd'
                        ],
                    ],
                    'size'=>'sd',
                    'containerOptions'=>[
                        'style'=>'padding-right:0px',
                    ],
                    'formOptions'=>['action'=>'update-worker-block', ],
                ],
            ],
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute'=>'worker_block_status',
                'readonly'=>function($dataProvider){
                    if($dataProvider->worker_block_status==0){
                        return false;
                    }else{
                        return true;
                    }
                },
                'format' => 'raw',
                'label' => '封号状态',
                'value' => function ($dataProvider) {
                    if($dataProvider->worker_block_status==0){
                        return '开启';
                    }else{
                        return '关闭';
                    }
                },
                'editableOptions' => [
                    'header' => '修改封号状态',
                    'inputType' => \kartik\editable\Editable::INPUT_RADIO_LIST,
                    'options' => [
                        //'items'=>['0' => '女', '1' => '男'],
                    ],
                    'formOptions'=>['action'=>'update-worker-block',],
                ],
            ]
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
    ]);
    Pjax::end();
    ?>
    </div>