<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;
use common\models\Shop;
use kartik\builder\TabularForm;
use kartik\widgets\SwitchInput;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2; // or kartik\select2\Select2
use kartik\tabs\TabsX;

use yii\helpers\ArrayHelper;
//var_dump($model->workertypeshow);
/**
 * @var yii\web\View $this
 * @var common\models\Worker $model
 */
$this->title = $model->worker_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="worker-view">

    <?= DetailView::widget([
        'model' => $model,
        'condensed' => false,
        'hover' => true,
        'mode' => Yii::$app->request->get('edit') == 't' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
        'panel' => [
            'heading' => $this->title,
            'type' => DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'worker_name',
            'worker_phone',
            [
                'attribute'=>'worker_idcard',
                'displayOnly' => true
            ],
            'worker_photo',
            'worker_work_city',
            'worker_work_area',
            'worker_work_street',
            [
                'attribute' => 'worker_type',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'name'=>'worker_type',
                    'class'=>\kartik\widgets\Select2::className(),
                    'data' => [1 => '自有', 2=>'非自有'],
                    'hideSearch' => true,
                    'options'=>[
                        'placeholder' => '选择阿姨身份',
                    ]
                ],
                'value'=>$model->WorkerTypeShow,
            ],
            [
                'attribute' => 'worker_rule_id',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class'=>\kartik\widgets\Select2::className(),
                    'data' =>  ArrayHelper::map($model->WorkerAllRules, 'id', 'worker_rule_name'),
                    'hideSearch' => true,
                    'options'=>[
                        'placeholder' => '选择阿姨身份',
                    ]
                ],
                'value'=>$model->getWorkerRuleShow($model->worker_rule_id),
            ],
            [
                'attribute' => 'worker_auth_status',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => \kartik\widgets\SwitchInput::classname()
                ],
                'value'=>$model->WorkerAuthStatusShow,
            ],
            [
                'attribute' => 'worker_ontrial_status',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => \kartik\widgets\SwitchInput::classname()
                ],
                'value'=>$model->WorkerOntrialStatusShow,
            ],
            [
                'attribute' => 'worker_onboard_status',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => \kartik\widgets\SwitchInput::classname()
                ],
                'value'=>$model->WorkerOnboardStatusShow,
            ],
            [
                'attribute' => 'worker_is_blacklist',
                'type' => DetailView::INPUT_TEXT,
                'displayOnly' => true,
                'value'=>$model->WorkerIsBlacListkShow,

            ],
            [
                'attribute' => 'worker_is_block',
                'type' => DetailView::INPUT_TEXT,
                'displayOnly' => true,
                'value'=>$model->WorkerIsBlockShow,

            ],
            [
                'attribute' => 'created_ad',
                'type' => DetailView::INPUT_TEXT,
                'format' => ['datetime', 'php:Y年m月d日 H:i'],
                'displayOnly' => true
            ],
        ],
        'deleteOptions' => [
            'url' => ['delete', 'id' => $model->id],
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ],
        'enableEditMode' => true,
    ]) ?>

</div>
