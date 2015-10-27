<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\operation\OperationServerCardCustomerSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-server-card-customer-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
	 <div class="col-md-2">
	 <?= $form->field($model, 'order_code') ?>
	 </div>
	 <div class="col-md-2">
	  <?= $form->field($model, 'card_no') ?>
	 </div>
	   <div class="col-md-2">
	   <?= $form->field($model, 'card_name') ?>
	 </div>
	  <div class="col-md-2">
	  <?= $form->field($model, 'customer_name') ?>
	 </div>
	  <div class="col-md-2">
	   <?= $form->field($model, 'customer_phone') ?>
	 </div>
	  <div class="col-md-2">
	   <?= $form->field($model, 'freeze_flag') ?>
	 </div>
	
    <?php // $form->field($model, 'id') ?>

    <?php // $form->field($model, 'order_id') ?>

    <?php // $form->field($model, 'order_code') ?>

    <?php // $form->field($model, 'card_id') ?>

    <?php // $form->field($model, 'card_no') ?>

    <?php // echo $form->field($model, 'card_name') ?>

    <?php // echo $form->field($model, 'card_type') ?>

    <?php // echo $form->field($model, 'card_level') ?>

    <?php // echo $form->field($model, 'pay_value') ?>

    <?php // echo $form->field($model, 'par_value') ?>

    <?php // echo $form->field($model, 'reb_value') ?>

    <?php // echo $form->field($model, 'res_value') ?>

    <?php // echo $form->field($model, 'customer_id') ?>

    <?php // echo $form->field($model, 'customer_name') ?>

    <?php // echo $form->field($model, 'customer_phone') ?>

    <?php // echo $form->field($model, 'use_scope') ?>

    <?php // echo $form->field($model, 'buy_at') ?>

    <?php // echo $form->field($model, 'valid_at') ?>

    <?php // echo $form->field($model, 'activated_at') ?>

    <?php // echo $form->field($model, 'freeze_flag') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
