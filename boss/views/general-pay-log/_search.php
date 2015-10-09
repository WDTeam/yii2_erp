<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var boss\models\GeneralPayLogSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="general-pay-log-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'general_pay_log_price') ?>

    <?= $form->field($model, 'general_pay_log_shop_name') ?>

    <?= $form->field($model, 'general_pay_log_eo_order_id') ?>

    <?= $form->field($model, 'general_pay_log_transaction_id') ?>

    <?php // echo $form->field($model, 'general_pay_log_status') ?>

    <?php // echo $form->field($model, 'pay_channel_id') ?>

    <?php // echo $form->field($model, 'general_pay_log_json_aggregation') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
