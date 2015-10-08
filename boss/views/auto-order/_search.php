<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var boss\models\AutoOrderSerach $model
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

    <?php // echo $form->field($model, 'order_before_status_dict_id') ?>

    <?php // echo $form->field($model, 'order_before_status_name') ?>

    <?php // echo $form->field($model, 'order_status_dict_id') ?>

    <?php // echo $form->field($model, 'order_status_name') ?>

    <?php // echo $form->field($model, 'order_flag_send') ?>

    <?php // echo $form->field($model, 'order_flag_urgent') ?>

    <?php // echo $form->field($model, 'order_flag_exception') ?>

    <?php // echo $form->field($model, 'order_service_type_id') ?>

    <?php // echo $form->field($model, 'order_service_type_name') ?>

    <?php // echo $form->field($model, 'order_src_id') ?>

    <?php // echo $form->field($model, 'order_src_name') ?>

    <?php // echo $form->field($model, 'channel_id') ?>

    <?php // echo $form->field($model, 'order_channel_name') ?>

    <?php // echo $form->field($model, 'order_channel_order_num') ?>

    <?php // echo $form->field($model, 'customer_id') ?>

    <?php // echo $form->field($model, 'order_ip') ?>

    <?php // echo $form->field($model, 'order_customer_phone') ?>

    <?php // echo $form->field($model, 'order_booked_begin_time') ?>

    <?php // echo $form->field($model, 'order_booked_end_time') ?>

    <?php // echo $form->field($model, 'order_booked_count') ?>

    <?php // echo $form->field($model, 'address_id') ?>

    <?php // echo $form->field($model, 'order_address') ?>

    <?php // echo $form->field($model, 'order_unit_money') ?>

    <?php // echo $form->field($model, 'order_money') ?>

    <?php // echo $form->field($model, 'order_booked_worker_id') ?>

    <?php // echo $form->field($model, 'order_customer_need') ?>

    <?php // echo $form->field($model, 'order_customer_memo') ?>

    <?php // echo $form->field($model, 'order_cs_memo') ?>

    <?php // echo $form->field($model, 'order_pay_type') ?>

    <?php // echo $form->field($model, 'pay_channel_id') ?>

    <?php // echo $form->field($model, 'order_pay_channel_name') ?>

    <?php // echo $form->field($model, 'order_pay_flow_num') ?>

    <?php // echo $form->field($model, 'order_pay_money') ?>

    <?php // echo $form->field($model, 'order_use_acc_balance') ?>

    <?php // echo $form->field($model, 'card_id') ?>

    <?php // echo $form->field($model, 'order_use_card_money') ?>

    <?php // echo $form->field($model, 'coupon_id') ?>

    <?php // echo $form->field($model, 'order_use_coupon_money') ?>

    <?php // echo $form->field($model, 'promotion_id') ?>

    <?php // echo $form->field($model, 'order_use_promotion_money') ?>

    <?php // echo $form->field($model, 'order_lock_status') ?>

    <?php // echo $form->field($model, 'worker_id') ?>

    <?php // echo $form->field($model, 'worker_type_id') ?>

    <?php // echo $form->field($model, 'order_worker_type_name') ?>

    <?php // echo $form->field($model, 'order_worker_send_type') ?>

    <?php // echo $form->field($model, 'shop_id') ?>

    <?php // echo $form->field($model, 'comment_id') ?>

    <?php // echo $form->field($model, 'order_customer_hidden') ?>

    <?php // echo $form->field($model, 'order_pop_pay_money') ?>

    <?php // echo $form->field($model, 'invoice_id') ?>

    <?php // echo $form->field($model, 'checking_id') ?>

    <?php // echo $form->field($model, 'admin_id') ?>

    <?php // echo $form->field($model, 'isdel') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('order', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('order', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
