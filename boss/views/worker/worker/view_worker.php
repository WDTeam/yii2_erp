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

use dbbase\models\Shop;
use boss\models\worker\Worker;
use boss\models\worker\WorkerIdentityConfig;
use boss\models\worker\workerExt;
/**
 * @var yii\web\View $this
 * @var dbbase\models\Worker $model
 */
$this->title = $model->worker_name;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workers'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;

?>
<div class="worker-view">
    <?= DetailView::widget([
        'model' => $model,
        'condensed' => false,
        'hover' => true,
        'formOptions'=>[
            'options'=>[
                'enctype'=>'multipart/form-data'
            ],
            'enableAjaxValidation' => true,
            'validationUrl'=>Yii::$app->urlManager->createUrl(['worker/worker/ajax-validate-worker-info','worker_id'=>$model->id]),
        ],

        'mode' => Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
        'panel' => [
            'heading' => $this->title,
            'type' => DetailView::TYPE_INFO,
        ],
        'attributes' => [
            [
                'attribute' => 'worker_work_city',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class'=>\kartik\widgets\Select2::className(),
                    'name' => 'worker_identity_id',
                    'hideSearch' => true,
                    'data' => $model::getOnlineCityList(),
                    'options' => ['placeholder' => '选择城市', 'inline' => true],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'pluginEvents'=> [
                        "change" => "function() {
                            $('#select2-worker-shop_id-container>.select2-selection__clear').mousedown();
                            $('.select2-selection__choice__remove').each(function(index,obj){
                                   $('.select2-selection__choice__remove').click()
                            });

                         }",
                    ]
                ],
                'value'=>Worker::getOnlineCityName($model->worker_work_city),
            ],
            [
                'attribute' => 'shop_id',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class'=>\kartik\widgets\Select2::className(),
                    'initValueText' => Worker::getShopName($model->shop_id), // set the initial display text
                    'options' => ['placeholder' => '选择门店'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 0,
                        'ajax' => [
                            'url' => Url::to(['show-shop']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {
                                    city_id:$("#worker-worker_work_city").val(),
                                    q:params.term,
                              };
                            }')
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(city) { return city.text; }'),
                        'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                    ],
                ],
                'value'=>Worker::getShopName($model->shop_id),
            ],
            [
                'attribute' =>'worker_photo',
                'type' => DetailView::INPUT_FILEINPUT,
                'widgetOptions'=>[
                    'options'=>[
                        'accept' => 'image/*',
                        'name'=>'Worker[worker_photo]'
                    ],
                    'pluginOptions' => [
                        'showPreview' => true,
                        'showCaption' => false,
                        'showRemove' => true,
                        'showUpload' => false,
                        'initialPreview'=>[
                            Worker::getWorkerPhotoShow($model->worker_photo)
                        ],

                    ],
                    'pluginLoading'=>true,
                ],
                'value'=>$model->worker_photo
            ],
            [
                'attribute' => 'worker_source',
                'editModel'=>$model->workerExtRelation,
                'viewModel'=>$model->workerExtRelation,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class'=>\kartik\widgets\Select2::className(),
                    'data' => workerExt::getSourceConfigList(),
                    'hideSearch' => true,
                    'options'=>[
                        'placeholder' => '选择招聘来源',
                    ]
                ],
                'value'=>WorkerExt::getWorkerSourceShow($model->workerExtRelation->worker_source),
                'label'=>'招聘来源'
            ],
            'worker_name',
            'worker_phone',
            [
                'attribute'=>'worker_idcard',
                'displayOnly' => true
            ],
            [   'attribute' => 'worker_district',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class'=>\kartik\widgets\Select2::className(),
                    'name' => 'worker_district',
                    'hideSearch' => false,
                    'data' => Worker::getDistrictList(),
                    'options' => ['placeholder' => '选择商圈','multiple' => true],
                    'pluginOptions' => [
                        'tags' => true,
                        'maximumInputLength' => 10,
                        'ajax' => [
                            'url' => Url::to(['show-district']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {
                            city_id: $("#worker-worker_work_city").val(),
                            name: params.term
                        }; }')
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(content) { return content.text; }'),
                        'templateSelection' => new JsExpression('function (content) { return content.text; }'),
                    ],
                ],
                'value'=>$model::getWorkerDistrictShow($model->id),
            ],

            //'worker_work_area',
            //'worker_work_street',

            [
                'attribute' => 'worker_sex',
                'editModel'=>$model->workerExtRelation,
                'viewModel'=>$model->workerExtRelation,
                'type' => DetailView::INPUT_RADIO_LIST,
                'items' => [0=>'女',1=>'男'],
                'label'=>'阿姨性别',
                'value'=>$model::getWorkerSexShow($model->workerExtRelation->worker_sex),
            ],

            [
                'attribute' => 'worker_age',
                'viewModel'=>$model->workerExtRelation,
                'editModel'=>$model->workerExtRelation,
                'type' => DetailView::INPUT_TEXT,
                'label'=>'阿姨年龄',
                'value'=>$model->workerExtRelation->worker_age

            ],
            [
                'attribute' => 'worker_height',
                'editModel'=>$model->workerExtRelation,
                'viewModel'=>$model->workerExtRelation,
                'type' => DetailView::INPUT_TEXT,
                'label'=>'阿姨身高(cm)',
                'value'=>workerExt::getWorkerHeightShow($model->workerExtRelation->worker_height),
            ],

            [
                'attribute' => 'worker_edu',
                'editModel'=>$model->workerExtRelation,
                'viewModel'=>$model->workerExtRelation,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class'=>\kartik\widgets\Select2::className(),
                    'data' => workerExt::getEduConfigList(),
                    'hideSearch' => true,
                    'options'=>[
                        'placeholder' => '选择阿姨教育程度',
                    ]
                ],
                'label'=>'阿姨教育程度'
            ],
            [
                'attribute' => 'worker_is_health',
                'editModel'=>$model->workerExtRelation,
                'viewModel'=>$model->workerExtRelation,
                'type' => DetailView::INPUT_RADIO_LIST,
                'items' => [1=>'有',0=>'无'],
                'label'=>'阿姨是否有健康证',
                'value'=>Worker::getWorkerIsHealthShow($model->workerExtRelation->worker_is_health),
            ],
            [
                'attribute' => 'worker_is_insurance',
                'editModel'=>$model->workerExtRelation,
                'viewModel'=>$model->workerExtRelation,
                'type' => DetailView::INPUT_RADIO_LIST,
                'items' => [1=>'是',0=>'否'],
                'label'=>'阿姨是否上保险',
                'value'=>Worker::getWorkerIsInsuranceShow($model->workerExtRelation->worker_is_insurance),
            ],

            [
                'attribute' => 'worker_bank_name',
                'editModel'=>$model->workerExtRelation,
                'viewModel'=>$model->workerExtRelation,
                'type' => DetailView::INPUT_TEXT,
                'label'=>'开户银行'
            ],
            [
                'attribute' => 'worker_bank_from',
                'editModel'=>$model->workerExtRelation,
                'viewModel'=>$model->workerExtRelation,
                'type' => DetailView::INPUT_TEXT,
                'label'=>'银行卡开户网点'
            ],
            [
                'attribute' => 'worker_bank_area',
                'editModel'=>$model->workerExtRelation,
                'viewModel'=>$model->workerExtRelation,
                'type' => DetailView::INPUT_TEXT,
                'label'=>'银行卡开户地'
            ],
            [
                'attribute' => 'worker_bank_card',
                'editModel'=>$model->workerExtRelation,
                'viewModel'=>$model->workerExtRelation,
                'type' => DetailView::INPUT_TEXT,
                'label'=>'银行卡号',
            ],
            [
                'columns'=>[
//                    [
//                        'attribute' => 'worker_live_province',
//                        'editModel'=>$model->workerExtRelation,
//                        'viewModel'=>$model->workerExtRelation,
//                        'type' => DetailView::INPUT_TEXT,
//                    ],
                    [
                        'attribute' => 'worker_live_province',
                        'editModel'=>$model->workerExtRelation,
                        'viewModel'=>$model->workerExtRelation,
                        'label'=>'阿姨居住地',
                        'type' => DetailView::INPUT_WIDGET,
                        'widgetOptions' => [
                            'class'=>\kartik\widgets\Select2::className(),
                            'name' => 'worker_district',
                            'hideSearch' => false,
                            'data' => Worker::getAreaListByLevel(1),
                            'options' => ['placeholder' => '选择省份'],
                            'pluginOptions' => [
                                'maximumInputLength' => 10,
                            ],
                            'pluginEvents'=> [
                                "change" => "function() {
                                $('#select2-workerext-worker_live_city-container>.select2-selection__clear').mousedown();
                                $('#select2-workerext-worker_live_area-container>.select2-selection__clear').mousedown();
                             }",
                            ]
                        ],
                        'value'=>$model::getArea($model->workerExtRelation->worker_live_province),
                    ],
                    [
                        'attribute' => 'worker_live_city',
                        'editModel'=>$model->workerExtRelation,
                        'viewModel'=>$model->workerExtRelation,
                        'labelColOptions'=>['style'=>'display:none'],
                        'type' => DetailView::INPUT_WIDGET,
                        'widgetOptions' => [
                            'class'=>\kartik\widgets\Select2::className(),
                            'hideSearch' => false,
                            'data' => Worker::getAreaListByLevel(2),
                            'options' => ['placeholder' => '选择城市'],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'maximumInputLength' => 10,
                                'ajax' => [
                                    'url' => Url::to(['show-area']),
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params) { return {
                                        parent_id: $("#workerext-worker_live_province").val(),
                                        name: params.term
                                    }; }')
                                ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(content) { return content.text; }'),
                                'templateSelection' => new JsExpression('function (content) { return content.text; }'),
                            ],
                            'pluginEvents'=> [
                                "change" => "function() {
                                $('#select2-workerext-worker_live_area-container>.select2-selection__clear').mousedown();
                             }",
                            ]
                        ],
                        'value'=>$model::getArea($model->workerExtRelation->worker_live_city),
                    ],
                    [
                        'attribute' => 'worker_live_area',
                        'editModel'=>$model->workerExtRelation,
                        'viewModel'=>$model->workerExtRelation,
                        'labelColOptions'=>['style'=>'display:none'],
                        'type' => DetailView::INPUT_WIDGET,
                        'widgetOptions' => [
                            'class'=>\kartik\widgets\Select2::className(),
                            'hideSearch' => false,
                            'data' => Worker::getAreaListByParentId($model->workerExtRelation->worker_live_city),
                            'options' => ['placeholder' => '选择区县'],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'maximumInputLength' => 10,
                                'ajax' => [
                                    'url' => Url::to(['show-area']),
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params) { return {
                                        parent_id: $("#workerext-worker_live_city").val(),
                                        name: params.term
                                    }; }')
                                ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(content) { return content.text; }'),
                                'templateSelection' => new JsExpression('function (content) { return content.text; }'),
                            ],
                        ],
                        'value'=>$model::getArea($model->workerExtRelation->worker_live_area),
                    ],
//                    [
//                        'attribute' => 'worker_live_city',
//                        'editModel'=>$model->workerExtRelation,
//                        'viewModel'=>$model->workerExtRelation,
//                        'type' => DetailView::INPUT_TEXT,
//                        'labelColOptions'=>['style'=>'display:none']
//                    ],
//                    [
//                        'attribute' => 'worker_live_area',
//                        'editModel'=>$model->workerExtRelation,
//                        'viewModel'=>$model->workerExtRelation,
//                        'type' => DetailView::INPUT_TEXT,
//                        'labelColOptions'=>['style'=>'display:none']
//                    ],
                ]
            ],
            [
                'attribute' => 'worker_live_street',
                'editModel'=>$model->workerExtRelation,
                'viewModel'=>$model->workerExtRelation,
                'type' => DetailView::INPUT_TEXT,
            ],
            [
                'attribute' => 'worker_type',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'name'=>'worker_type',
                    'class'=>\kartik\widgets\Select2::className(),
                    'data' => [1 => '自有', 2=>'非自有'],
                    'hideSearch' => true,
                    'options'=>[
                        'placeholder' => '选择阿姨类型',
                    ]
                ],
                'value'=>Worker::getWorkerTypeShow($model->worker_type),
            ],

            [
                'attribute' => 'worker_identity_id',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class'=>\kartik\widgets\Select2::className(),
                    'data' => WorkerIdentityConfig::getWorkerIdentityList(),
                    'hideSearch' => true,
                    'options'=>[
                        'placeholder' => '选择阿姨身份',
                    ]
                ],
                'value'=>WorkerIdentityConfig::getWorkerIdentityShow($model->worker_identity_id),
            ],
            [
                'attribute' => 'worker_star',
                'type' => DetailView::INPUT_TEXT,
                'displayOnly' => true,
                'value'=>$model->worker_star.'星',

            ],
            [
                'attribute' => 'worker_is_vacation',
                'type' => DetailView::INPUT_TEXT,
                'displayOnly' => true,
                'value'=>Worker::getWorkerIsVacationShow($model->worker_is_blacklist),

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
                'method' => 'post',
            ],
        ],
        'enableEditMode' => true,
    ]) ?>

</div>
