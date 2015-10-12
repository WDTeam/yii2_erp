<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;

use kartik\builder\TabularForm;
use kartik\widgets\SwitchInput;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2; // or kartik\select2\Select2
use kartik\tabs\TabsX;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;

use common\models\Shop;
use core\models\worker\Worker;
use core\models\worker\WorkerRuleConfig;
use core\models\worker\WorkerExt;

$extModel = \core\models\worker\WorkerExt::findOne('16391');
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
        'mode' => Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
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
            [
                'attribute' => 'worker_work_city',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class'=>\kartik\widgets\Select2::className(),
                    'name' => 'worker_rule_id',
                    'hideSearch' => true,
                    'data' => $model::getOnlineCityList(),
                    'options' => ['placeholder' => '选择城市', 'inline' => true],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ],
                'value'=>Worker::getOnlineCityName($model->worker_work_city),
            ],
            [
                'attribute' => 'shop_id',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class'=>\kartik\widgets\Select2::className(),
                    'initValueText' => Worker::getShopName($model->shop_id), // set the initial display text
                    'options' => ['placeholder' => 'Search for a shop_menager ...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 0,
                        'ajax' => [
                            'url' => \yii\helpers\Url::to(['show-shop']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(city) { return city.text; }'),
                        'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                    ],
                ],
                'value'=>Worker::getShopName($model->shop_id),
            ],
            //'worker_work_area',
            //'worker_work_street',
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
                'value'=>Worker::getWorkerTypeShow($model->worker_type),
            ],
            [
                'attribute' => 'worker_rule_id',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class'=>\kartik\widgets\Select2::className(),
                    'data' => WorkerRuleConfig::getWorkerRuleList(),
                    'hideSearch' => true,
                    'options'=>[
                        'placeholder' => '选择阿姨身份',
                    ]
                ],
                'value'=>WorkerRuleConfig::getWorkerRuleShow($model->worker_rule_id),
            ],

            [
                'attribute' => 'worker_age',
                'viewModel'=>$extModel,
                'editModel'=>$extModel,
                'type' => DetailView::INPUT_TEXT,
                'label'=>'阿姨年龄',
                'value'=>$model->workerExt->worker_age

            ],
            [
                'attribute' => 'worker_sex',
                'editModel'=>$model->workerExt,
                'viewModel'=>$model->workerExt,
                'type' => DetailView::INPUT_RADIO_LIST,
                'items' => [0=>'女',1=>'男'],
                'label'=>'阿姨性别'
            ],
            [
                'attribute' => 'worker_birth',
                'editModel'=>$model->workerExt,
                'viewModel'=>$model->workerExt,
                'type' => DetailView::INPUT_DATE,
                'label'=>'阿姨生日'
            ],
            [
                'attribute' => 'worker_edu',
                'editModel'=>$model->workerExt,
                'viewModel'=>$model->workerExt,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class'=>\kartik\widgets\Select2::className(),
                    'data' => WorkerExt::getEduConfigList(),
                    'hideSearch' => true,
                    'options'=>[
                        'placeholder' => '选择阿姨教育程度',
                    ]
                ],
                'label'=>'阿姨教育程度'
            ],
            [
                'attribute' => 'worker_hometown',
                'editModel'=>$model->workerExt,
                'viewModel'=>$model->workerExt,
                'type' => DetailView::INPUT_TEXT,
                'label'=>'阿姨籍贯'
            ],
            [
                'attribute' => 'worker_is_health',
                'editModel'=>$model->workerExt,
                'viewModel'=>$model->workerExt,
                'type' => DetailView::INPUT_RADIO_LIST,
                'items' => [0=>'是',1=>'否'],
                'label'=>'阿姨是否有健康证'
            ],
            [
                'attribute' => 'worker_is_insurance',
                'editModel'=>$model->workerExt,
                'viewModel'=>$model->workerExt,
                'type' => DetailView::INPUT_RADIO_LIST,
                'items' => [0=>'是',1=>'否'],
                'label'=>'阿姨是否上保险'
            ],
            [
                'attribute' => 'worker_source',
                'editModel'=>$model->workerExt,
                'viewModel'=>$model->workerExt,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class'=>\kartik\widgets\Select2::className(),
                    'data' => WorkerExt::getSourceConfigList(),
                    'hideSearch' => true,
                    'options'=>[
                        'placeholder' => '选择阿姨来源',
                    ]
                ],
                'label'=>'阿姨来源'
            ],
            [
                'attribute' => 'worker_bank_name',
                'editModel'=>$model->workerExt,
                'viewModel'=>$model->workerExt,
                'type' => DetailView::INPUT_TEXT,
                'label'=>'开户银行'
            ],
            [
                'attribute' => 'worker_bank_from',
                'editModel'=>$model->workerExt,
                'viewModel'=>$model->workerExt,
                'type' => DetailView::INPUT_TEXT,
                'label'=>'银行卡开户网点'
            ],
            [
                'attribute' => 'worker_bank_card',
                'editModel'=>$model->workerExt,
                'viewModel'=>$model->workerExt,
                'type' => DetailView::INPUT_TEXT,
                'label'=>'银行卡号',
            ],
            [
                'columns'=>[
                    [
                        'attribute' => 'worker_live_province',
                        'editModel'=>$model->workerExt,
                        'viewModel'=>$model->workerExt,
                        'type' => DetailView::INPUT_TEXT,
                        'label'=>'阿姨居住地',
                    ],
                    [
                        'attribute' => 'worker_live_city',
                        'editModel'=>$model->workerExt,
                        'viewModel'=>$model->workerExt,
                        'type' => DetailView::INPUT_TEXT,
                        'labelColOptions'=>['style'=>'display:none']
                    ],
                    [
                        'attribute' => 'worker_live_area',
                        'editModel'=>$model->workerExt,
                        'viewModel'=>$model->workerExt,
                        'type' => DetailView::INPUT_TEXT,
                        'labelColOptions'=>['style'=>'display:none']
                    ],
                ]
            ],
            [
                'attribute' => 'worker_live_street',
                'editModel'=>$model->workerExt,
                'viewModel'=>$model->workerExt,
                'type' => DetailView::INPUT_TEXT,
            ],
            [
                'attribute' => 'worker_auth_status',
                'type' => DetailView::INPUT_RADIO_LIST,
                'items'=>['0'=>'已审核','1'=>'已试工','2'=>'已上岗'],
                //'value'=>Worker::getWorkerAuthStatusShow($model->worker_auth_status),
                'label'=>'阿姨审核状态'
            ],
            [
                'attribute' => 'worker_is_blacklist',
                'type' => DetailView::INPUT_TEXT,
                'displayOnly' => true,
                'value'=>Worker::getWorkerIsBlacListkShow($model->worker_is_blacklist),

            ],
            [
                'attribute' => 'worker_is_block',
                'type' => DetailView::INPUT_TEXT,
                'displayOnly' => true,
                'value'=>Worker::getWorkerIsBlockShow($model->worker_is_block),

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
