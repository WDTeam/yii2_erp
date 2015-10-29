<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\OperationServiceCardInfoSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-service-card-info-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
	<div class="col-md-2">
	 <?= $form->field($model, 'service_card_info_name') ?>
	</div>
	<div class="col-md-2">
	 <?= $form->field($model, 'service_card_info_card_type') ?>
	</div>
	<div class="col-md-2">
	 <?= $form->field($model, 'service_card_info_card_level') ?>
	</div>
	<div class="col-md-2">
	 <?= $form->field($model, 'service_card_info_par_value') ?>
	</div>
	<div class="col-md-2">
	 <?= $form->field($model, 'service_card_info_reb_value') ?>
	</div>
	<div class="col-md-2">
	 <?= $form->field($model, 'service_card_info_valid_days') ?>
	</div>

    <?php // echo $form->field($model, 'id') ?>

    <?php // echo $form->field($model, 'service_card_info_name') ?>

    <?php // echo $form->field($model, 'service_card_info_card_type') ?>

    <?php // echo $form->field($model, 'service_card_info_card_level') ?>

    <?php // echo $form->field($model, 'service_card_info_par_value') ?>

    <?php // echo $form->field($model, 'service_card_info_reb_value') ?>

    <?php // echo $form->field($model, 'service_card_info_use_scope') ?>

    <?php // echo $form->field($model, 'service_card_info_valid_days') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
