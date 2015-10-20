<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var core\models\worker\WorkerTaskSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="worker-task-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'worker_task_name') ?>

    <?= $form->field($model, 'worker_task_start') ?>

    <?= $form->field($model, 'worker_task_end') ?>

    <?= $form->field($model, 'worker_type') ?>

    <?php // echo $form->field($model, 'worker_rule_id') ?>

    <?php // echo $form->field($model, 'worker_task_city_id') ?>

    <?php // echo $form->field($model, 'worker_task_description') ?>

    <?php // echo $form->field($model, 'worker_task_description_url') ?>

    <?php // echo $form->field($model, 'worker_task_conditions') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
