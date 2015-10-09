<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationAdvertRelease */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="operation-advert-release-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?php //echo $form->field($model, 'operation_platform_id')->dropDownList($platforms)->label('所属平台') ?>
    
    <?php //echo !$model->isNewRecord ? $form->field($model, 'operation_platform_version_id')->dropDownList($versions)->label('所属版本') : $form->field($model, 'operation_platform_version_id')->dropDownList(['选择版本'])->label('所属版本') ?>
    
    <?= $form->field($model, 'city_id')->dropDownList($citys)->label('第一步：选择要发布的目标城市');?>
    <div class="form-group" id="step1">
        <?php //= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <div class="form-group" id="step2">
        
    </div>
    <div class="form-group" id="step3"></div>
    <div class="form-group" id="step4"></div>
    <div class="form-group hide" id="step5">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
