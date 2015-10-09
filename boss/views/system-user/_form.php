<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use boss\models\SystemUser;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\SystemUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'repassword')->passwordInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'role')->dropDownList(SystemUser::getArrayRole()) ?>

    <?= $form->field($model, 'status')->dropDownList(SystemUser::getArrayStatus()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
