<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\operation\OperationServerCardSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-server-card-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
  
	 <div class="col-md-3">
    <?= $form->field($model, 'card_name') ?>
	</div>
   <div class="col-md-3">
	<?= $form->field($model, 'card_type')->dropDownList($deploy['card_type'], ['prompt'=>'请选择']) ?>
	</div>
	 <div class="col-md-3">
	<?= $form->field($model, 'card_level')->dropDownList($deploy['card_level'], ['prompt'=>'请选择',]) ?>
	 </div>
    
	 <div class="col-md-3">
    <?= $form->field($model, 'par_value') ?>
	</div>
	 <?php //echo  $form->field($model, 'id') ?>
	  <?php // echo $form->field($model, 'card_type') ?>
	  <?php //echo $form->field($model, 'card_level') ?>

    <?php // echo $form->field($model, 'reb_value') ?>

    <?php // echo $form->field($model, 'use_scope') ?>

    <?php // echo $form->field($model, 'valid_days') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
