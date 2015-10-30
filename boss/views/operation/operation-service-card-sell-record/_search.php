<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\OperationServiceCardSellRecordSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-service-card-sell-record-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

	<div class="col-md-2">
	 <?= $form->field($model, 'service_card_sell_record_code') ?>
	</div>
	<div class="col-md-2">
	 <?= $form->field($model, 'customer_phone') ?>
	</div>
	<div class="col-md-2">
	 <?= $form->field($model, 'customer_trans_record_pay_mode') ?>
	</div>
	<div class="col-md-2">
	 <?= $form->field($model, 'customer_trans_record_pay_channel') ?>
	</div>
	<div class="col-md-4">
	 <?= $form->field($model, 'customer_trans_record_pay_money') ?>
	</div>
	<div class="col-md-6">
	  <?= $form->field($model, 'customer_trans_record_paid_at_min')->widget(DatePicker::classname(), [
    		'name' => 'customer_trans_record_paid_at_min',
    		'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    		'pluginOptions' => [
    		'autoclose' => true,
    		'format' => 'yyyy-mm-dd'
    		]
            ]);  ?>
	 
	</div>
	<div class="col-md-6">
	<?= $form->field($model, 'customer_trans_record_paid_at_max')->widget(DatePicker::classname(), [
    		'name' => 'customer_trans_record_paid_at_max',
    		'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    		'pluginOptions' => [
    		'autoclose' => true,
    		'format' => 'yyyy-mm-dd'
    		]
            ]);  ?>
	</div>
    <?php // echo $form->field($model, 'id') ?>

    <?php // echo $form->field($model, 'service_card_sell_record_code') ?>

    <?php // echo $form->field($model, 'customer_id') ?>

    <?php // echo $form->field($model, 'customer_phone') ?>

    <?php // echo $form->field($model, 'service_card_info_card_id') ?>

    <?php // echo $form->field($model, 'service_card_info_name') ?>

    <?php // echo $form->field($model, 'service_card_sell_record_money') ?>

    <?php // echo $form->field($model, 'service_card_sell_record_channel_id') ?>

    <?php // echo $form->field($model, 'service_card_sell_record_channel_name') ?>

    <?php // echo $form->field($model, 'service_card_sell_record_status') ?>

    <?php // echo $form->field($model, 'customer_trans_record_pay_mode') ?>

    <?php // echo $form->field($model, 'pay_channel_id') ?>

    <?php // echo $form->field($model, 'customer_trans_record_pay_channel') ?>

    <?php // echo $form->field($model, 'customer_trans_record_transaction_id') ?>

    <?php // echo $form->field($model, 'customer_trans_record_pay_money') ?>

    <?php // echo $form->field($model, 'customer_trans_record_pay_account') ?>

    <?php // echo $form->field($model, 'customer_trans_record_paid_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
