<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model boss\models\ComplaintOrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="complaint-order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'order_id') ?>

    <?= $form->field($model, 'worker_id') ?>

    <?= $form->field($model, 'complaint_type') ?>

    <?= $form->field($model, 'complaint_section') ?>

    <?php // echo $form->field($model, 'complaint_level') ?>

    <?php // echo $form->field($model, 'complaint_content') ?>

    <?php // echo $form->field($model, 'complaint_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
