<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationPlatformVersion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="operation-platform-version-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="hide"><?= $form->field($model, 'operation_platform_id')->hiddenInput(['value' => $platform_id]) ?></div>

    <?= $form->field($model, 'operation_platform_version_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
