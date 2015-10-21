<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var core\models\worker\WorkerTaskSearch $searchModel
 */

$this->title = Yii::t('app', 'Worker Tasks');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worker-task-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Worker Task',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
//         'filterModel' => $searchModel,
        'toolbar' =>[
            'content'=>Html::a('<i class="glyphicon glyphicon-plus"></i>', [
                'worker-task/create'
            ], [
                'class' => 'btn btn-default',
                'title' => Yii::t('app', '添加任务')
            ]),
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'worker_task_name',
            'worker_task_start:date',
            'worker_task_end:date',
            [
                'attribute'=>'worker_type',
                'value'=>function($model){
                    return $model->getWorkerTypeLabels();
                },
            ],
            [
                'attribute'=>'worker_rule_id',
                'value'=>function($model){
                    return $model->getWorkerRuleLabels();
                },
            ],
            [
                'attribute'=>'worker_task_city_id',
                'value'=>function($model){
                    return $model->getWorkerCityLabels();
                },
            ],
//             'worker_task_description', 
//             'worker_task_description_url:url', 
//             'conditions:ntext', 
//            'created_at', 
//            'updated_at', 
//            'is_del', 

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {update} {copy}',
                'buttons' => [
                    'copy'=>function ($url, $model) {
                        return Html::a('复制', [
                            'worker-task/copy',
                            'id' => $model->id
                        ], [
                            'title' => Yii::t('app', '任务复制'),
                            'data-toggle'=>'modal',
                            'data-target'=>'#modal',
                            'data-id'=>$model->id,
                            'class'=>'btn btn-success btn-sm',
                        ]);
                    },
                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,




        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),
            'after'=>false,
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
<?php echo Modal::widget([
    'header' => '<h4 class="modal-title">任务复制</h4>',
    'id' =>'modal',
]);?>
<?php $this->registerJs(<<<JSCONTENT
    $('.join-list-btn').click(function(){
        $('#modal .modal-body').html('加载中……');
        $('#modal .modal-body').eq(0).load(this.href);
    });
JSCONTENT
);?>
