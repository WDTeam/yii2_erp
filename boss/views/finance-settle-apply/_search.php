<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var boss\models\FinanceSettleApplySearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-settle-apply-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'worder_id') ?>

    <?= $form->field($model, 'worder_tel') ?>

    <?= $form->field($model, 'worker_type_id') ?>

    <?= $form->field($model, 'worker_type_name') ?>

    <?php // echo $form->field($model, 'finance_settle_apply_money') ?>

    <?php // echo $form->field($model, 'finance_settle_apply_man_hour') ?>

    <?php // echo $form->field($model, 'finance_settle_apply_order_money') ?>

    <?php // echo $form->field($model, 'finance_settle_apply_order_cash_money') ?>

    <?php // echo $form->field($model, 'finance_settle_apply_non_order_money') ?>

    <?php // echo $form->field($model, 'finance_settle_apply_status') ?>

    <?php // echo $form->field($model, 'finance_settle_apply_cycle') ?>

    <?php // echo $form->field($model, 'finance_settle_apply_reviewer') ?>

    <?php // echo $form->field($model, 'isdel') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
