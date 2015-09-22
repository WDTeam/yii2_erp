<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var boss\models\FinanceRefundSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-refund-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'finance_refund_tel') ?>

    <?= $form->field($model, 'finance_refund_money') ?>

    <?= $form->field($model, 'finance_refund_stype') ?>

    <?= $form->field($model, 'finance_refund_reason') ?>

    <?php // echo $form->field($model, 'finance_refund_discount') ?>

    <?php // echo $form->field($model, 'finance_refund_pay_create_time') ?>

    <?php // echo $form->field($model, 'finance_pay_channel_id') ?>

    <?php // echo $form->field($model, 'finance_pay_channel_name') ?>

    <?php // echo $form->field($model, 'finance_refund_pay_flow_num') ?>

    <?php // echo $form->field($model, 'finance_refund_pay_status') ?>

    <?php // echo $form->field($model, 'finance_refund_worker_id') ?>

    <?php // echo $form->field($model, 'finance_refund_worker_tel') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
