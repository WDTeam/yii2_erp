<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\coupon\CouponRule $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="coupon-rule-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'couponrule_name') ?>

    <?= $form->field($model, 'couponrule_channelname') ?>

    <?= $form->field($model, 'couponrule_classify') ?>

    <?= $form->field($model, 'couponrule_category') ?>

    <?php // echo $form->field($model, 'couponrule_category_name') ?>

    <?php // echo $form->field($model, 'couponrule_type') ?>

    <?php // echo $form->field($model, 'couponrule_type_name') ?>

    <?php // echo $form->field($model, 'couponrule_service_type_id') ?>

    <?php // echo $form->field($model, 'couponrule_service_type_name') ?>

    <?php // echo $form->field($model, 'couponrule_commodity_id') ?>

    <?php // echo $form->field($model, 'couponrule_commodity_name') ?>

    <?php // echo $form->field($model, 'couponrule_city_limit') ?>

    <?php // echo $form->field($model, 'couponrule_city_id') ?>

    <?php // echo $form->field($model, 'couponrule_city_name') ?>

    <?php // echo $form->field($model, 'couponrule_customer_type') ?>

    <?php // echo $form->field($model, 'couponrule_customer_type_name') ?>

    <?php // echo $form->field($model, 'couponrule_get_start_time') ?>

    <?php // echo $form->field($model, 'couponrule_get_end_time') ?>

    <?php // echo $form->field($model, 'couponrule_use_start_time') ?>

    <?php // echo $form->field($model, 'couponrule_use_end_time') ?>

    <?php // echo $form->field($model, 'couponrule_code') ?>

    <?php // echo $form->field($model, 'couponrule_Prefix') ?>

    <?php // echo $form->field($model, 'couponrule_use_end_days') ?>

    <?php // echo $form->field($model, 'couponrule_promote_type') ?>

    <?php // echo $form->field($model, 'couponrule_promote_type_name') ?>

    <?php // echo $form->field($model, 'couponrule_order_min_price') ?>

    <?php // echo $form->field($model, 'couponrule_price') ?>

    <?php // echo $form->field($model, 'couponrule_price_sum') ?>

    <?php // echo $form->field($model, 'couponrule_code_num') ?>

    <?php // echo $form->field($model, 'couponrule_code_max_customer_num') ?>

    <?php // echo $form->field($model, 'is_disabled') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <?php // echo $form->field($model, 'system_user_id') ?>

    <?php // echo $form->field($model, 'system_user_name') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
