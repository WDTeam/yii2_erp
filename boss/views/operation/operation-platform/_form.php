<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationPlatform */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="operation-platform-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'operation_platform_name')->textInput(['maxlength' => 10]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
