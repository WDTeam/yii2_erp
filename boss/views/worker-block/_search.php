<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var boss\models\WorkerBlockSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="worker-block-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'worker_id') ?>

    <?= $form->field($model, 'worker_block_type') ?>

    <?= $form->field($model, 'worker_block_reason') ?>

    <?= $form->field($model, 'worker_block_start') ?>

    <?php // echo $form->field($model, 'worker_block_finish') ?>

    <?php // echo $form->field($model, 'created_ad') ?>

    <?php // echo $form->field($model, 'updated_ad') ?>

    <?php // echo $form->field($model, 'admin_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
