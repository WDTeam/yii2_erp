<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var boss\models\FinancePopOrderLogSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-pop-order-log-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'finance_pay_order_num') ?>

    <?= $form->field($model, 'finance_pop_order_number') ?>

    <?= $form->field($model, 'finance_pop_order_log_series_succeed_status') ?>

    <?= $form->field($model, 'finance_pop_order_log_series_succeed_status_time') ?>

    <?php // echo $form->field($model, 'finance_pop_order_log_finance_status') ?>

    <?php // echo $form->field($model, 'finance_pop_order_log_finance_status_time') ?>

    <?php // echo $form->field($model, 'finance_pop_order_log_finance_audit') ?>

    <?php // echo $form->field($model, 'finance_pop_order_log_finance_audit_time') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('boss', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('boss', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
