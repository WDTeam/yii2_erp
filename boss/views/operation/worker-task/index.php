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
                'create'
            ], [
                'class' => 'btn btn-default',
                'title' => Yii::t('app', '添加任务')
            ]),
        ],
        'columns' => [
//             ['class' => 'yii\grid\SerialColumn'],

            'id',
            'worker_task_name',
            [
                'label'=>'任务条件',
                'attribute'=>'conditions',
                'format'=>'raw',
                'value'=>function ($model){
                    return $this->render('show_conditions',['models'=>$model->getConditions()]);
                },
            ],
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
            [
                'attribute'=>'worker_task_reward_value',
                'value'=>function($model){
                    return $model->worker_task_reward_value.$model->getRewardUnit();
                }
            ],
            'worker_task_description', 
            [
                'label'=>'任务状态',
                'value'=>function($model){
                    return $model->getStatusLabel();
                }
            ],
//             'worker_task_description_url:url', 
            
           'created_at:date', 
//            'updated_at', 
//            'is_del', 

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>$model->worker_task_start>time()?
                    '{view} {update} {online}':
                    '{view} {online}',
                'buttons' => [
                    'online'=>function($url, $model){
                        if($model->worker_task_online==1){
                            return Html::a('下线', [
                                'set-online',
                                'id'=>$model->id,
                                'online'=>0
                            ],[
                                'onclick'=>'return confirm("是否要下线阿姨任务？");',
                            ]);
                        }else{
                            return Html::a('上线', [
                                'set-online',
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
            'before'=>'',
            'after'=>false,
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
