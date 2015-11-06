<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\coupon\CouponUserinfo $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="coupon-userinfo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'customer_id') ?>

    <?= $form->field($model, 'customer_tel') ?>

    <?= $form->field($model, 'coupon_userinfo_id') ?>

    <?= $form->field($model, 'coupon_userinfo_code') ?>

    <?php // echo $form->field($model, 'coupon_userinfo_name') ?>

    <?php // echo $form->field($model, 'coupon_userinfo_price') ?>

    <?php // echo $form->field($model, 'coupon_userinfo_gettime') ?>

    <?php // echo $form->field($model, 'coupon_userinfo_usetime') ?>

    <?php // echo $form->field($model, 'coupon_userinfo_endtime') ?>

    <?php // echo $form->field($model, 'order_code') ?>

    <?php // echo $form->field($model, 'system_user_id') ?>

    <?php // echo $form->field($model, 'system_user_name') ?>

    <?php // echo $form->field($model, 'is_used') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
