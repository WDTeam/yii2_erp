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

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'card_name') ?>

    <?= $form->field($model, 'card_type') ?>

    <?= $form->field($model, 'card_level') ?>

    <?= $form->field($model, 'par_value') ?>

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
