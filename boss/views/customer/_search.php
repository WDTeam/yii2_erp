<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var boss\models\Customer $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="customer-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'customer_name') ?>

    <?= $form->field($model, 'customer_sex') ?>

    <?= $form->field($model, 'customer_birth') ?>

    <?= $form->field($model, 'customer_photo') ?>

    <?php // echo $form->field($model, 'customer_phone') ?>

    <?php // echo $form->field($model, 'customer_email') ?>

    <?php // echo $form->field($model, 'region_id') ?>

    <?php // echo $form->field($model, 'customer_live_address_detail') ?>

    <?php // echo $form->field($model, 'customer_balance') ?>

    <?php // echo $form->field($model, 'customer_score') ?>

    <?php // echo $form->field($model, 'customer_level') ?>

    <?php // echo $form->field($model, 'customer_complaint_times') ?>

    <?php // echo $form->field($model, 'customer_src') ?>

    <?php // echo $form->field($model, 'channal_id') ?>

    <?php // echo $form->field($model, 'platform_id') ?>

    <?php // echo $form->field($model, 'customer_login_ip') ?>

    <?php // echo $form->field($model, 'customer_login_time') ?>

    <?php // echo $form->field($model, 'customer_is_vip') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <?php // echo $form->field($model, 'customer_del_reason') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('boss', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('boss', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
