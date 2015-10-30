<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\OperationServiceCardConsumeRecordSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-service-card-consume-record-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
	<div class="col-md-2">
	 <?= $form->field($model, 'customer_id') ?>
	</div>
	<div class="col-md-2">
	 <?= $form->field($model, 'customer_trans_record_transaction_id') ?>
	</div>
	<div class="col-md-2">
	 <?= $form->field($model, 'order_code') ?>
	</div>
	<div class="col-md-2">
	 <?= $form->field($model, 'service_card_with_customer_code') ?>
	</div>
	<div class="col-md-2">
	 <?= $form->field($model, 'service_card_consume_record_consume_type') ?>
	</div>
	<div class="col-md-2">
	 <?= $form->field($model, 'service_card_consume_record_business_type') ?>
	</div>

    <?php // echo $form->field($model, 'id') ?>

    <?php // echo $form->field($model, 'customer_id') ?>

    <?php // echo $form->field($model, 'customer_trans_record_transaction_id') ?>

    <?php // echo $form->field($model, 'order_id') ?>

    <?php // echo $form->field($model, 'order_code') ?>

    <?php // echo $form->field($model, 'service_card_with_customer _id') ?>

    <?php // echo $form->field($model, 'service_card_with_customer_code') ?>

    <?php // echo $form->field($model, 'service_card_consume_record_front_money') ?>

    <?php // echo $form->field($model, 'service_card_consume_record_behind_money') ?>

    <?php // echo $form->field($model, 'service_card_consume_record_consume_type') ?>

    <?php // echo $form->field($model, 'service_card_consume_record_business_type') ?>

    <?php // echo $form->field($model, 'service_card_consume_record_use_money') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
