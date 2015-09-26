<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationAdvertContent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="operation-advert-content-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'operation_advert_position_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operation_city_id')->textInput() ?>

    <?= $form->field($model, 'operation_city_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operation_advert_start_time')->textInput() ?>

    <?= $form->field($model, 'operation_advert_end_time')->textInput() ?>

    <?= $form->field($model, 'operation_advert_online_time')->textInput() ?>

    <?= $form->field($model, 'operation_advert_offline_time')->textInput() ?>

    <?= $form->field($model, 'operation_advert_picture')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operation_advert_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
