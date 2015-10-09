<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var core\models\order\OrderSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'order_code') ?>

    <?= $form->field($model, 'order_parent_id') ?>

    <?= $form->field($model, 'order_is_parent') ?>

    <?= $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'isdel') ?>

    <?php // echo $form->field($model, 'order_ip') ?>

    <?php // echo $form->field($model, 'order_service_type_id') ?>

    <?php // echo $form->field($model, 'order_service_type_name') ?>

    <?php // echo $form->field($model, 'order_src_id') ?>

    <?php // echo $form->field($model, 'order_src_name') ?>

    <?php // echo $form->field($model, 'channel_id') ?>

    <?php // echo $form->field($model, 'order_channel_name') ?>

    <?php // echo $form->field($model, 'order_unit_money') ?>

    <?php // echo $form->field($model, 'order_money') ?>

    <?php // echo $form->field($model, 'order_booked_count') ?>

    <?php // echo $form->field($model, 'order_booked_begin_time') ?>

    <?php // echo $form->field($model, 'order_booked_end_time') ?>

    <?php // echo $form->field($model, 'address_id') ?>

    <?php // echo $form->field($model, 'order_address') ?>

    <?php // echo $form->field($model, 'order_booked_worker_id') ?>

    <?php // echo $form->field($model, 'checking_id') ?>

    <?php // echo $form->field($model, 'order_cs_memo') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
