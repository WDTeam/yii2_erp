<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;
use kartik\date\DatePicker;

/**
 * @var yii\web\View $this
 * @var boss\models\WorkerSearch $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="worker-search">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_VERTICAL,
        //'id' => 'login-form-inline',
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class='col-md-2'>
        <?= $form->field($model, 'worker_work_city')->widget(Select2::classname(), [
            'name' => 'worker_rule_id',
            'hideSearch' => true,
            'data' => $model::getOnlineCityList(),
            'options' => ['placeholder' => '选择城市', 'inline' => true],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
    </div>
    <div class='col-md-3'>
        <?= $form->field($model, 'shop_id')->widget(Select2::classname(), [
            'initValueText' => '门店', // set the initial display text
            'options' => ['placeholder' => '搜索门店名称...', 'class' => 'col-md-2'],
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
        ]); ?>
    </div>
    <div class='col-md-2'>
        <?= $form->field($model, 'worker_name') ?>
    </div>
    <div class='col-md-2'>
        <?= $form->field($model, 'worker_phone') ?>
    </div>
    <?php // echo $form->field($model, 'worker_level') ?>

    <?php // echo $form->field($model, 'worker_auth_status') ?>

    <?php // echo $form->field($model, 'worker_ontrial_status') ?>

    <?php // echo $form->field($model, 'worker_onboard_status') ?>

    <?php // echo $form->field($model, 'worker_work_city') ?>

    <?php // echo $form->field($model, 'worker_work_area') ?>

    <?php // echo $form->field($model, 'worker_work_street') ?>

    <?php // echo $form->field($model, 'worker_work_lng') ?>

    <?php // echo $form->field($model, 'worker_work_lat') ?>

    <?php // echo $form->field($model, 'worker_type') ?>

    <?php // echo $form->field($model, 'worker_rule_id') ?>

    <?php // echo $form->field($model, 'worker_is_block') ?>

    <?php // echo $form->field($model, 'worker_is_blacklist') ?>

    <?php // echo $form->field($model, 'created_ad') ?>

    <?php // echo $form->field($model, 'updated_ad') ?>

    <?php // echo $form->field($model, 'isdel') ?>


    <div class='col-md-2' style="    margin-top: 22px;">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>