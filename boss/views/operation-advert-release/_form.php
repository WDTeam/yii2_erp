<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationAdvertRelease */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="operation-advert-release-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'operation_city_id')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'operation_platform_id')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'operation_platform_version_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operation_advert_position_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operation_advert_position_name')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'operation_advert_content_id')->textInput() ?>

    <?= $form->field($model, 'operation_advert_content_name')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
