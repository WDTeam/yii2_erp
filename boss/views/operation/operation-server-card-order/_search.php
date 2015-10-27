<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\operation\OperationServerCardOrderSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-server-card-order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'order_code') ?>

    <?= $form->field($model, 'usere_id') ?>

    <?= $form->field($model, 'order_customer_phone') ?>

    <?= $form->field($model, 'server_card_id') ?>

    <?php // echo $form->field($model, 'card_name') ?>

    <?php // echo $form->field($model, 'card_type') ?>

    <?php // echo $form->field($model, 'card_level') ?>

    <?php // echo $form->field($model, 'par_value') ?>

    <?php // echo $form->field($model, 'reb_value') ?>

    <?php // echo $form->field($model, 'order_money') ?>

    <?php // echo $form->field($model, 'order_src_id') ?>

    <?php // echo $form->field($model, 'order_src_name') ?>

    <?php // echo $form->field($model, 'order_channel_id') ?>

    <?php // echo $form->field($model, 'order_channel_name') ?>

    <?php // echo $form->field($model, 'order_lock_status') ?>

    <?php // echo $form->field($model, 'order_status_id') ?>

    <?php // echo $form->field($model, 'order_status_name') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'order_pay_type') ?>

    <?php // echo $form->field($model, 'pay_channel_id') ?>

    <?php // echo $form->field($model, 'pay_channel_name') ?>

    <?php // echo $form->field($model, 'order_pay_flow_num') ?>

    <?php // echo $form->field($model, 'order_pay_money') ?>

    <?php // echo $form->field($model, 'paid_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
