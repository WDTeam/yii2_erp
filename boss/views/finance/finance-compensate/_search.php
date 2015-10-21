<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var core\models\search\FinanceCompensate $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-compensate-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'finance_compensate_oa_code') ?>

    <?= $form->field($model, 'finance_complaint_id') ?>

    <?= $form->field($model, 'worker_id') ?>

    <?= $form->field($model, 'customer_id') ?>

    <?php // echo $form->field($model, 'finance_compensate_coupon') ?>

    <?php // echo $form->field($model, 'finance_compensate_money') ?>

    <?php // echo $form->field($model, 'finance_compensate_reason') ?>

    <?php // echo $form->field($model, 'finance_compensate_proposer') ?>

    <?php // echo $form->field($model, 'finance_compensate_auditor') ?>

    <?php // echo $form->field($model, 'comment') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
