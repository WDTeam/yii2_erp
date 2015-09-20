<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var boss\models\FinancePopOrderSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-pop-order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'finance_pop_order_number') ?>

    <?= $form->field($model, 'finance_order_channel_id') ?>

    <?= $form->field($model, 'finance_order_channel_title') ?>

    <?= $form->field($model, 'finance_pay_channel_id') ?>

    <?php // echo $form->field($model, 'finance_pay_channel_title') ?>

    <?php // echo $form->field($model, 'finance_pop_order_customer_tel') ?>

    <?php // echo $form->field($model, 'finance_pop_order_worker_uid') ?>

    <?php // echo $form->field($model, 'finance_pop_order_booked_time') ?>

    <?php // echo $form->field($model, 'finance_pop_order_booked_counttime') ?>

    <?php // echo $form->field($model, 'finance_pop_order_sum_money') ?>

    <?php // echo $form->field($model, 'finance_pop_order_coupon_count') ?>

    <?php // echo $form->field($model, 'finance_pop_order_coupon_id') ?>

    <?php // echo $form->field($model, 'finance_pop_order_order2') ?>

    <?php // echo $form->field($model, 'finance_pop_order_channel_order') ?>

    <?php // echo $form->field($model, 'finance_pop_order_order_type') ?>

    <?php // echo $form->field($model, 'finance_pop_order_status') ?>

    <?php // echo $form->field($model, 'finance_pop_order_finance_isok') ?>

    <?php // echo $form->field($model, 'finance_pop_order_discount_pay') ?>

    <?php // echo $form->field($model, 'finance_pop_order_reality_pay') ?>

    <?php // echo $form->field($model, 'finance_pop_order_order_time') ?>

    <?php // echo $form->field($model, 'finance_pop_order_pay_time') ?>

    <?php // echo $form->field($model, 'finance_pop_order_pay_status') ?>

    <?php // echo $form->field($model, 'finance_pop_order_pay_title') ?>

    <?php // echo $form->field($model, 'finance_pop_order_check_id') ?>

    <?php // echo $form->field($model, 'finance_pop_order_finance_time') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
