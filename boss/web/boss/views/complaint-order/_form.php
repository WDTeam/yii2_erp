<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model boss\models\ComplaintOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="complaint-order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_id')->textInput() ?>

    <?= $form->field($model, 'worker_id')->textInput() ?>

    <?= $form->field($model, 'complaint_type')->textInput() ?>

    <?= $form->field($model, 'complaint_section')->textInput() ?>

    <?= $form->field($model, 'complaint_level')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'complaint_content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'complaint_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
