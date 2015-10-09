<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var frontend\models\DemoSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'common_mobile') ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'auth_key') ?>

    <?= $form->field($model, 'password_hash') ?>

    <?php // echo $form->field($model, 'password_reset_token') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'idnumber') ?>

    <?php // echo $form->field($model, 'age') ?>

    <?php // echo $form->field($model, 'birthday') ?>

    <?php // echo $form->field($model, 'mobile') ?>

    <?php // echo $form->field($model, 'ecp') ?>

    <?php // echo $form->field($model, 'ecn') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'city') ?>

    <?php // echo $form->field($model, 'province') ?>

    <?php // echo $form->field($model, 'district') ?>

    <?php // echo $form->field($model, 'whatodo') ?>

    <?php // echo $form->field($model, 'from_type') ?>

    <?php // echo $form->field($model, 'when') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'isdel') ?>

    <?php // echo $form->field($model, 'study_status') ?>

    <?php // echo $form->field($model, 'study_time') ?>

    <?php // echo $form->field($model, 'notice_status') ?>

    <?php // echo $form->field($model, 'online_exam_time') ?>

    <?php // echo $form->field($model, 'online_exam_score') ?>

    <?php // echo $form->field($model, 'online_exam_mode') ?>

    <?php // echo $form->field($model, 'exam_result') ?>

    <?php // echo $form->field($model, 'operation_time') ?>

    <?php // echo $form->field($model, 'operation_score') ?>

    <?php // echo $form->field($model, 'test_status') ?>

    <?php // echo $form->field($model, 'test_situation') ?>

    <?php // echo $form->field($model, 'test_result') ?>

    <?php // echo $form->field($model, 'sign_status') ?>

    <?php // echo $form->field($model, 'user_status') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
