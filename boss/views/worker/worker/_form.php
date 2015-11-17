<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\Select2; // or kartik\select2\Select2
use kartik\grid\GridView;
use kartik\date\DatePicker;
use kartik\widgets\FileInput;
use boss\components\AreaCascade;

use core\models\worker\Worker;
use core\models\worker\WorkerExt;
use core\models\worker\WorkerRuleConfig;
use core\models\worker\WorkerIdentityConfig;
/**
 * @var yii\web\View $this
 * @var dbbase\models\Worker $worker
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="worker-form">

    <?php $form = ActiveForm::begin(
        [
            'type' => ActiveForm::TYPE_HORIZONTAL, 'id' => 'msg-form',
            'options' => ['enctype' => 'multipart/form-data'],
            //'enableAjaxValidation'=>false,
            'fieldConfig' => [
                //'template' => "{label}\n<div class=\"col-lg-8\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
                //'labelOptions' => ['class' => 'col-lg-1 control-label'],
            ],
            'enableAjaxValidation' => true,
            'validationUrl'=>Yii::$app->urlManager->createUrl(['worker/worker/ajax-validate-worker-info'])
        ]

    );
    //    echo '<fieldset id="w2"><div class="form-group field-operationcity-operation_city_name required">';
    //            echo '<label class="control-label col-md-2" for="operationcity-operation_city_name">选择城市</label>';
    //            echo '<div class="col-md-10">'.Yii::$app->areacascade->cascadeAll('OperationCity', ['class' => 'form-control']).'</div>';
    //            echo '</div></fieldset>';
    ?>
    <div class="panel panel-info">


        <div class="panel-heading"><h3 class="panel-title">基础信息</h3></div>
        <div class="panel-body">
            <?=  $form->field($worker, 'worker_work_city')->widget(Select2::classname(), [
                'name' => 'worker_work_city',
                'hideSearch' => true,
                'data' => $worker::getOnlineCityList(),
                'options' => ['placeholder' => '选择城市', 'inline' => true],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'pluginEvents'=> [
                    "change" => "function() {
                    $('#select2-worker-shop_id-container>.select2-selection__clear').mousedown();
                    $('.select2-selection__choice__remove').each(function(index,obj){
                         $('.select2-selection__choice__remove').click()
                    })
                    }",
                ]
            ]); ?>
            <?= $form->field($worker, 'shop_id')->widget(Select2::classname(), [
                'options' => ['placeholder' => '选择门店', 'class' => 'col-md-2'],
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
            ]); ?>

            </div>

            <div class="panel-heading"><h3 class="panel-title">阿姨基本信息</h3> </div>

            <div class="panel-body">
            <?= $form->field($worker, 'worker_photo')->widget(FileInput::classname(), [
                'options' => ['multiple' => true],
                'pluginOptions' => [
                    'previewFileType' => 'any',
                    'showPreview' => true,
                    'showCaption' => false,
                    'showRemove' => true,
                    'showUpload' => false,
                ]
            ])?>
            <?= $form->field($worker_ext, 'worker_source')->widget(Select2::classname(), [
                'name' => 'worker_source',
                'class'=>\kartik\widgets\Select2::className(),
                'data' => WorkerExt::getSourceConfigList(),
                'hideSearch' => true,
                'options'=>[
                    'placeholder' => '选择招聘来源',
                ]
            ]); ?>

            <?= $form->field($worker, 'worker_name')->textInput(['placeholder' => '输入阿姨姓名...', 'maxlength' => 10]); ?>
            <?= $form->field($worker, 'worker_phone')->textInput(['placeholder' => '输入阿姨手机...', 'maxlength' => 20]); ?>
            <?= $form->field($worker, 'worker_idcard')->textInput(['placeholder' => '输入阿姨身份证号...', 'maxlength' => 20]); ?>
            <?= $form->field($worker, 'worker_district')->widget(Select2::classname(), [
                'name' => 'worker_district',
                'hideSearch' => false,
                'data' => Worker::getDistrictList(),
                'options' => ['placeholder' => '选择阿姨商圈','multiple' => true],
                'pluginOptions' => [
                    'tags' => false,
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
            ]); ?>


            <?= $form->field($worker_ext, 'worker_sex')->radioList(['0' => '女', '1' => '男'], ['inline' => true]); ?>
            <?= $form->field($worker_ext, 'worker_age')->textInput(['placeholder' => '输入阿姨年龄...']); ?>
            <?= $form->field($worker_ext, 'worker_height')->textInput(['placeholder' => '输入阿姨身高...']); ?>

            <?php /*$form->field($worker_ext, 'worker_birth')->widget(DatePicker::classname(), [
                'name' => 'worker_birth',
                'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                'value' => date('Y-m-d', $worker_ext->worker_birth),
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]); */ ?>
            <?= $form->field($worker_ext, 'worker_edu')->widget(Select2::classname(), [
                'name' => 'worker_edu',
                'hideSearch' => true,
                'data' => $worker_ext::getEduConfigList(),
                'options' => ['placeholder' => '选择阿姨学历'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            <?php //$form->field($worker_ext, 'worker_hometown')->textInput(['placeholder' => '输入阿姨籍贯...']); ?>
            <?= $form->field($worker_ext, 'worker_is_health')->radioList(['1' => '是', '0' => '否'], ['inline' => true]); ?>
            <?= $form->field($worker_ext, 'worker_is_insurance')->radioList(['1' => '是', '0' => '否'], ['inline' => true]); ?>
            <?= $form->field($worker, 'worker_type')->radioList(['1' => '自有', '2' => '非自有'], ['inline' => true]); ?>
            <?= $form->field($worker, 'worker_identity_id')->widget(Select2::classname(), [
                'name' => 'worker_identity_id',
                'hideSearch' => true,
                'data' =>WorkerIdentityConfig::getWorkerIdentityList(),
                'options' => ['placeholder' => '选择阿姨身份'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>

            <?= $form->field($worker_ext, 'worker_live_province')->widget(Select2::classname(), [
                'name' => 'worker_live_province',
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
            ]); ?>
            <?= $form->field($worker_ext, 'worker_live_city')->widget(Select2::classname(), [
                'name' => 'worker_live_city',
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
            ]); ?>
            <?= $form->field($worker_ext, 'worker_live_area')->widget(Select2::classname(), [
                'name' => 'worker_district',
                'hideSearch' => false,
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
            ]); ?>
            <?= $form->field($worker_ext, 'worker_live_street')->textInput(['placeholder' => '输入阿姨居住详细地址...', 'maxlength' => 10]); ?>
        </div>


        <div class="panel-heading"><h3 class="panel-title">结算相关信息</h3></div>
        <div class="panel-body">
            <?= $form->field($worker_ext, 'worker_bank_name')->textInput(['placeholder' => '输入开户银行...']); ?>
            <?= $form->field($worker_ext, 'worker_bank_from')->textInput(['placeholder' => '输入银行卡开户网点...']); ?>
            <?= $form->field($worker_ext, 'worker_bank_area')->textInput(['placeholder' => '输入银行卡开户地...']); ?>
            <?= $form->field($worker_ext, 'worker_bank_card')->textInput(['placeholder' => '输入银行卡号...']); ?>
        </div>




    </div>
<?php
echo Html::submitButton('创建', ['class' => $worker->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

ActiveForm::end(); ?>
</div>
