<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

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
            'worker_type',
            'worker_rule_id', 
            'worker_task_city_id', 
//             'worker_task_description', 
//             'worker_task_description_url:url', 
//             'conditions:ntext', 
//            'created_at', 
//            'updated_at', 
//            'is_del', 

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['worker-task/view','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('yii', 'Edit'),
                                                  ]);}

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
