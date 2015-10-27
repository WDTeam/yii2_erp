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

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'trade_id') ?>

    <?= $form->field($model, 'cus_card_id') ?>

    <?= $form->field($model, 'front_value') ?>

    <?= $form->field($model, 'behind_value') ?>

    <?php // echo $form->field($model, 'use_value') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
