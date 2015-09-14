<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Question */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="question-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php $form->field($model, 'courseware_id')->textInput() ?>

    <?= $form->field($model, 'options')->label('答案选项格式（一行一个，如：“A、内容”。注意每行中的第一个字用于和正确答案比配。）')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'is_multi')->radioList(['单选题', '多选题'])->label("请注意：0，是单选；1，是多选") ?>
    
    <?= $form->field($model, 'correct_options')->textInput(['maxlength' => true])->label('正确答案（多选题请于半角逗号“,”隔开）') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app','Cancel'), ['index', 'courseware_id'=>$model->courseware_id, 'category_id' => $model->category_id], ['class' => 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
