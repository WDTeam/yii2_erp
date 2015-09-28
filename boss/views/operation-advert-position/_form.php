<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationAdvertPosition */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="operation-advert-position-form">
    <?php $form = ActiveForm::begin(); ?>
    <?php
    if($model->isNewRecord){
        $cs = [];
        foreach ($citys as $city){
            $cs[$city->city_id] = $city->city_name;
        }
        echo '<label class="control-label" for="operationadvertposition-operation_city_id">显示城市</label>';
        echo '<div class="form-group">'.Html::checkbox('OperationAdvertPosition[useall]', false, ['class' => 'form-group', 'id' => 'operationadvertposition-useall', 'value' => 'all', 'label'=> '所有城市使用统一设置' ]).'</div>';
        echo Html::checkboxList('OperationAdvertPosition[citys]', '', $cs, ['class' => 'form-group', 'id' => 'operationadvertposition-citysList']);
    }
    ?>
    
    <?= $form->field($model, 'operation_advert_position_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operation_platform_id')->dropDownList($platforms)->label('所属平台') ?>

    <?= !$model->isNewRecord ? $form->field($model, 'operation_platform_version_id')->dropDownList($versions)->label('所属版本') : $form->field($model, 'operation_platform_version_id')->dropDownList(['选择版本'])->label('所属版本') ?>

    <?= $form->field($model, 'operation_advert_position_width')->textInput() ?>

    <?= $form->field($model, 'operation_advert_position_height')->textInput() ?>

    <div class="form-group">
        <?= Html::button($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'id' => 'advert-position-create']) ?>
        <?= Html::button(Yii::t('app', 'Cancel'), ['class' => 'btn btn-info', 'id' => 'CancelWin']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
