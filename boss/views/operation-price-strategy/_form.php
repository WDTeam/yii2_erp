<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OperationPriceStrategy */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="operation-price-strategy-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'operation_price_strategy_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operation_price_strategy_unit')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operation_price_strategy_lowest_consume_unit')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
