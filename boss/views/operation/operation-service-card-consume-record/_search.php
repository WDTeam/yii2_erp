<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\OperationServiceCardConsumeRecordSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-service-card-consume-record-search panel panel-info">
	<div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 服务卡消费记录搜索</h3>
    </div>

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
	<div class="panel-body row">
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
	</div>
	<div class="panel-body row">
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
	<div class="col-md-10">
		</div>
    <div class="col-md-2">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

	</div>

    <?php ActiveForm::end(); ?>

</div>
