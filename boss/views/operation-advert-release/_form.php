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
    
    <?= $form->field($model, 'operation_platform_id')->dropDownList($platforms)->label('所属平台') ?>
    
    <?= !$model->isNewRecord ? $form->field($model, 'operation_platform_version_id')->dropDownList($versions)->label('所属版本') : $form->field($model, 'operation_platform_version_id')->dropDownList(['选择版本'])->label('所属版本') ?>

    <?= $form->field($model, 'operation_advert_position_id')->dropDownList($positions);?>
    
    <?php
    echo '<div class="form-group field-operationadvertrelease-operation_advert_contents">';
    echo '<label class="control-label" for="operationadvertrelease-operation_advert_position_id">广告内容</label>';
    echo Html::checkboxList('operation_advert_contents[]', null, $contents);
    echo '</div>';
//    foreach($contents as $k => $v){
//        echo '<li><label>'.Html::textInput('operation_advert_position_id', $k).$v.'</label></li>';
//    }
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
