<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var boss\models\WorkerSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="worker-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'shop_id') ?>

    <?= $form->field($model, 'worker_name') ?>

    <?= $form->field($model, 'worker_phone') ?>

    <?= $form->field($model, 'worker_idcard') ?>

    <?php // echo $form->field($model, 'worker_password') ?>

    <?php // echo $form->field($model, 'worker_photo') ?>

    <?php // echo $form->field($model, 'worker_level') ?>

    <?php // echo $form->field($model, 'worker_auth_status') ?>

    <?php // echo $form->field($model, 'worker_ontrial_status') ?>

    <?php // echo $form->field($model, 'worker_onboard_status') ?>

    <?php // echo $form->field($model, 'worker_work_city') ?>

    <?php // echo $form->field($model, 'worker_work_area') ?>

    <?php // echo $form->field($model, 'worker_work_street') ?>

    <?php // echo $form->field($model, 'worker_work_lng') ?>

    <?php // echo $form->field($model, 'worker_work_lat') ?>

    <?php // echo $form->field($model, 'worker_rule') ?>

    <?php // echo $form->field($model, 'worker_identify_id') ?>

    <?php // echo $form->field($model, 'worker_is_block') ?>

    <?php // echo $form->field($model, 'created_ad') ?>

    <?php // echo $form->field($model, 'updated_ad') ?>

    <?php // echo $form->field($model, 'isdel') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
