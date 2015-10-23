<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var core\models\finance\FinanceWorkerOrderIncomeSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-worker-order-income-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'worder_id') ?>

    <?= $form->field($model, 'order_id') ?>

    <?= $form->field($model, 'finance_worker_order_income_type') ?>

    <?= $form->field($model, 'finance_worker_order_income') ?>

    <?php // echo $form->field($model, 'finance_worker_order_complete_time') ?>

    <?php // echo $form->field($model, 'order_booked_count') ?>

    <?php // echo $form->field($model, 'isSettled') ?>

    <?php // echo $form->field($model, 'finance_worker_order_income_starttime') ?>

    <?php // echo $form->field($model, 'finance_worker_order_income_endtime') ?>

    <?php // echo $form->field($model, 'finance_settle_apply_id') ?>

    <?php // echo $form->field($model, 'isdel') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
