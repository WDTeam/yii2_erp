<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use boss\models\worker\Worker;
use common\models\SystemUser;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
//var_dump($workerBlockData->workerBlock->worker_block_start_time);die;
?>

    <div style="height:1000px">
    <?php
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $workerBlockLogData,
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
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'worker_block_operate_time',
                'format' => 'raw',
                'label' => '操作时间',
                'value' => function ($dataProvider) {
                    return date('Y-m-d H:i:s',$dataProvider->worker_block_operate_time);
                },
            ],
            [
                'attribute'=>'worker_block_operate_bak',
                'format' => 'raw',
                'label' => '操作备注',
            ],
            [
                'attribute'=>'worker_block_operate_id',
                'format' => 'raw',
                'label' => '操作管理员',
                'value' => function ($dataProvider) {
                    return SystemUser::findOne(['id'=>$dataProvider->worker_block_operate_id])->username;
                },

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
    ]);
    Pjax::end();
    ?>
    </div>