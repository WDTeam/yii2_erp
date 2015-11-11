<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\OperationPayChannelSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-pay-channel-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'operation_pay_channel_name') ?>

    <?= $form->field($model, 'operation_pay_channel_type') ?>

    <?= $form->field($model, 'operation_pay_channel_rate') ?>

    <?= $form->field($model, 'system_user_id') ?>

    <?php // echo $form->field($model, 'system_user_name') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
