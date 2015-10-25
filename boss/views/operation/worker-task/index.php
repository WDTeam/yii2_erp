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
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

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
//             ['class' => 'yii\grid\SerialColumn'],

            'id',
            'worker_task_name',
            'worker_task_start:date',
            'worker_task_end:date',
            [
                'attribute'=>'worker_task_cycle',
                'value'=>function($model){
                    return $model->getCycleLabel();
                },
            ],
            [
                'attribute'=>'worker_type',
                'label'=>'阿姨角色',
                'value'=>function($model){
                    return $model->getWorkerTypeLabels();
                },
            ],
            [
                'attribute'=>'worker_rule_id',
                'label'=>'阿姨身份',
                'value'=>function($model){
                    return $model->getWorkerRuleLabels();
                },
            ],
            [
                'attribute'=>'worker_task_city_id',
                'label'=>'适用城市',
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
                'template'=>'{view} {update} {online}',
                'buttons' => [
                    'online'=>function($url, $model){
                        if($model->worker_task_online==1){
                            return Html::a('下线', [
                                'worker-task/set-online',
                                'id'=>$model->id,
                                'online'=>0
                            ]);
                        }else{
                            return Html::a('上线', [
                                'worker-task/set-online',
                                'id'=>$model->id,
                                'online'=>1
                            ]);
                        }
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
