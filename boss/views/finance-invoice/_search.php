<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var boss\models\FinanceInvoiceSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-invoice-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'finance_invoice_serial_number') ?>

    <?= $form->field($model, 'finance_invoice_customer_tel') ?>

    <?= $form->field($model, 'finance_invoice_worker_tel') ?>

    <?= $form->field($model, 'pay_channel_pay_id') ?>

    <?php // echo $form->field($model, 'pay_channel_pay_title') ?>

    <?php // echo $form->field($model, 'finance_invoice_pay_status') ?>

    <?php // echo $form->field($model, 'admin_confirm_uid') ?>

    <?php // echo $form->field($model, 'finance_invoice_enrolment_time') ?>

    <?php // echo $form->field($model, 'finance_invoice_money') ?>

    <?php // echo $form->field($model, 'finance_invoice_title') ?>

    <?php // echo $form->field($model, 'finance_invoice_address') ?>

    <?php // echo $form->field($model, 'finance_invoice_status') ?>

    <?php // echo $form->field($model, 'finance_invoice_check_id') ?>

    <?php // echo $form->field($model, 'finance_invoice_number') ?>

    <?php // echo $form->field($model, 'finance_invoice_service_money') ?>

    <?php // echo $form->field($model, 'finance_invoice_corp_email') ?>

    <?php // echo $form->field($model, 'finance_invoice_corp_address') ?>

    <?php // echo $form->field($model, 'finance_invoice_corp_name') ?>

    <?php // echo $form->field($model, 'finance_invoice_district_id') ?>

    <?php // echo $form->field($model, 'classify_id') ?>

    <?php // echo $form->field($model, 'classify_title') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
