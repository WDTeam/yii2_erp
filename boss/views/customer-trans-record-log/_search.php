<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var boss\models\CustomerTransRecordLogControllerSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="customer-trans-record-log-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'customer_id') ?>

    <?= $form->field($model, 'order_id') ?>

    <?= $form->field($model, 'order_channel_id') ?>

    <?= $form->field($model, 'customer_trans_record_order_channel') ?>

    <?php // echo $form->field($model, 'pay_channel_id') ?>

    <?php // echo $form->field($model, 'customer_trans_record_pay_channel') ?>

    <?php // echo $form->field($model, 'customer_trans_record_mode') ?>

    <?php // echo $form->field($model, 'customer_trans_record_mode_name') ?>

    <?php // echo $form->field($model, 'customer_trans_record_promo_code_money') ?>

    <?php // echo $form->field($model, 'customer_trans_record_coupon_money') ?>

    <?php // echo $form->field($model, 'customer_trans_record_cash') ?>

    <?php // echo $form->field($model, 'customer_trans_record_pre_pay') ?>

    <?php // echo $form->field($model, 'customer_trans_record_online_pay') ?>

    <?php // echo $form->field($model, 'customer_trans_record_online_balance_pay') ?>

    <?php // echo $form->field($model, 'customer_trans_record_online_service_card_on') ?>

    <?php // echo $form->field($model, 'customer_trans_record_online_service_card_pay') ?>

    <?php // echo $form->field($model, 'customer_trans_record_online_service_card_current_balance') ?>

    <?php // echo $form->field($model, 'customer_trans_record_online_service_card_befor_balance') ?>

    <?php // echo $form->field($model, 'customer_trans_record_compensate_money') ?>

    <?php // echo $form->field($model, 'customer_trans_record_refund_money') ?>

    <?php // echo $form->field($model, 'customer_trans_record_money') ?>

    <?php // echo $form->field($model, 'customer_trans_record_order_total_money') ?>

    <?php // echo $form->field($model, 'customer_trans_record_total_money') ?>

    <?php // echo $form->field($model, 'customer_trans_record_current_balance') ?>

    <?php // echo $form->field($model, 'customer_trans_record_befor_balance') ?>

    <?php // echo $form->field($model, 'customer_trans_record_transaction_id') ?>

    <?php // echo $form->field($model, 'customer_trans_record_remark') ?>

    <?php // echo $form->field($model, 'customer_trans_record_verify') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('common\CustomerTransRecordLog', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('common\CustomerTransRecordLog', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
