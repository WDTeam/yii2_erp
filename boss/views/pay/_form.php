<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Pay */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pay-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'customer_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pay_money')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pay_actual_money')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pay_source')->textInput() ?>

    <?= $form->field($model, 'pay_mode')->textInput() ?>

    <?= $form->field($model, 'pay_status')->textInput() ?>

    <?= $form->field($model, 'pay_transaction_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pay_eo_order_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pay_memo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pay_is_coupon')->textInput() ?>

    <?= $form->field($model, 'admin_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'worker_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'handle_admin_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pay_verify')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_del')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
