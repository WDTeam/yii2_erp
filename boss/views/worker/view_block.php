<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use core\models\worker\Worker;
use yii\bootstrap\Modal;
use common\models\WorkerBlock;
use yii\data\ActiveDataProvider;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
$this->title = Yii::t('app', '阿姨管理');
$this->params['breadcrumbs'][] = $this->title;
$worker = Worker::find()->select('worker_name')->where(['id'=>$worker_id])->one();
$workerBlockModel = WorkerBlock::find()->where(['worker_id'=>$worker_id])->one();

if($workerBlockModel!==null){
    $workerBlockModel->worker_block_start_time = date('Y-m-d',$workerBlockModel->worker_block_start_time);
    $workerBlockModel->worker_block_finish_time = date('Y-m-d',$workerBlockModel->worker_block_finish_time);
}else{
    $workerBlockModel = new WorkerBlock();
    $workerBlockModel->worker_block_status = 1;
}

$workerBlockData = new ActiveDataProvider([
    'query' => WorkerBlock::find()->where(['worker_id'=>$worker_id]),
]);

?>
    <div style="height:1000px">
    <?php
    Modal::begin([
        'header' => '<h4 class="modal-title">封号操作</h4>',
        'toggleButton' => ['label' => '<i class="fa fa-fw fa-lock"></i>操作封号信息', 'class' => 'btn btn-primary']
    ]);
    echo $this->render('create_block',['worker_id'=>$worker_id,'worker_name'=>$worker['worker_name'],'workerBlockModel'=>$workerBlockModel]);
    Modal::end();
    ?>
    <div style="height:7px"></div>
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
            [
                'attribute'=>'worker_id',
                'label'=>'阿姨姓名',
                'value'=>function($dataProvider){
                    return Worker::findOne(['id'=>$dataProvider->worker_id])->worker_name;
                },

            ],
            [
                'attribute'=>'worker_block_start_time',
                'label' => '封号开始时间',
                'value' => function ($dataProvider) {
                    return date('Y-m-d',$dataProvider->worker_block_start_time);
                },

            ],
            [
                'attribute'=>'worker_block_finish_time',
                'width'=>'30%',
                'label' => '封号结束时间',
                'value' => function ($dataProvider) {
                    return date('Y-m-d',$dataProvider->worker_block_finish_time);
                },
            ],
            [
                'attribute'=>'worker_block_status',
                'width'=>'30%',
                'label' => '封号状态',
                'value' => function ($dataProvider) {
                    if($dataProvider->worker_block_status==1){
                        return '开启';
                    }else{
                        return '关闭';
                    }
                },
            ],

        ],
//        'responsive' => true,
//        'hover' => true,
//        'condensed' => true,
        'panel' => [
            //'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '. Html::encode($this->title) . ' </h3>',
            //'type' => 'info',

            //'after' => '',
            //'showFooter' => true
        ],
        'summary'=>false,
    ]);
    Pjax::end();
    ?>
    </div>

<script>
</script>