<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use core\models\worker\WorkerTask;
use core\models\order\Order;

/**
 * @var yii\web\View $this
 * @var core\models\worker\WorkerTask $model
 */

$order = Order::find()->one();
$order->trigger(Order::EVENT_ACCEPT_BY_WORKER);

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Worker Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="worker-task-view">

    <?= DetailView::widget([
        'model' => $model,
        'condensed'=>false,
        'hover'=>true,
        'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
        'panel'=>[
            'heading'=>$model->worker_task_name,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'id',
            'worker_task_name',
//             'worker_task_cycle',
            'worker_task_start:date',
            'worker_task_end:date',
            [
                'attribute'=>'worker_type',
                'value'=>$model->getWorkerTypeLabels()
            ],
            [
                'attribute'=>'worker_rule_id',
                'value'=>$model->getWorkerRuleLabels()
            ],
            [
                'attribute'=>'worker_task_city_id',
                'value'=>$model->getWorkerCityLabels()
            ],
            'worker_task_description',
            'worker_task_description_url:url',
            [
                'label'=>'条件',
                'format'=>'raw',
                'value'=>$this->render('show_conditions',['models'=>$model->getConditions()])
            ],
//             'created_at',
//             'updated_at',
//             'is_del',
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->id],
        'data'=>[
            'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
            'method'=>'post',
        ],
        ],
        'enableEditMode'=>false,
    ]) ?>

</div>
