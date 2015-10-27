<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\operation\OperationServerCardRecordSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-server-card-record-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
	<div class="col-md-2">
	<?= $form->field($model, 'id') ?>
	</div>
	<div class="col-md-2">
	<?= $form->field($model, 'order_code') ?>
	</div>
	<div class="col-md-2">
	<?= $form->field($model, 'card_no') ?>
	</div>
	<div class="col-md-2">
	<?= $form->field($model, 'use_value') ?>
	</div>
	<div class="col-md-2">
	<?= $form->field($model, 'consume_type') ?>
	</div>
	<div class="col-md-2">
	<?= $form->field($model, 'business_type') ?>
	</div>
    <?php // $form->field($model, 'id') ?>

    <?php // $form->field($model, 'trade_id') ?>

    <?php // $form->field($model, 'cus_card_id') ?>

    <?php // $form->field($model, 'front_value') ?>

    <?php // $form->field($model, 'behind_value') ?>

    <?php // echo $form->field($model, 'use_value') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-col-md-12">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
