<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OperationCategoryType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="operation-category-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'operation_category_type_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operation_category_id')->textInput() ?>

    <?= $form->field($model, 'operation_category_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operation_category_type_introduction')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'operation_category_type_english_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operation_category_type_start_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operation_category_type_end_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operation_category_type_service_time_slot')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'operation_category_type_service_interval_time')->textInput() ?>

    <?= $form->field($model, 'operation_price_strategy_id')->textInput() ?>

    <?= $form->field($model, 'operation_price_strategy_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operation_category_type_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operation_category_type_balance_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operation_category_type_additional_cost')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operation_category_type_lowest_consume')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operation_category_type_price_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'operation_category_type_market_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operation_tags')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'operation_category_type_app_ico')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'operation_category_type_pc_ico')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
