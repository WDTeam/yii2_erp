<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
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
	 <?= $form->field($model, 'service_card_info_card_type')->widget(Select2::classname(), [
        'name' => '服务卡类型',
        'hideSearch' => true,
        'data' => $config['type'],
        'options' => ['placeholder' => '选择服务卡类型','class' => 'col-md-2'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
   
    ?>
	</div>
	<div class="col-md-2">
	<?= $form->field($model, 'service_card_info_card_level')->widget(Select2::classname(), [
        'name' => '服务卡级别',
        'hideSearch' => true,
        'data' => $config['level'],
        'options' => ['placeholder' => '选择服务卡级别','class' => 'col-md-2'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
   
    ?>
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
