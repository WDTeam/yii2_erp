<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use boss\components\AreaCascade;

/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationAdvertContent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="operation-advert-content-form">
    <?php $form = ActiveForm::begin(['options' =>['enctype' => "multipart/form-data"]]); ?>
    <?= $form->field($model, 'operation_city_id')->dropDownList($cityList, ['maxlength' => true])->label('所属城市') ?>
    <?= $form->field($model, 'operation_platform_id')->checkboxList($platformList, ['maxlength' => true])->label('所属平台') ?>
    <div class="form-group field-operationadvertcontent-operation_platform_version_id hide">
        <label class="control-label" for="operationadvertcontent-operation_platform_version_id">平台版本</label>
        <div id="platformVersion"></div>
    </div>
    <?php // $form->field($model, 'operation_platform_version_id')->dropDownList($versionList, ['maxlength' => true])->label('所属版本') ?>
    <?= $form->field($model, 'operation_advert_position_name')->textInput(['maxlength' => true])?>
    <?php
//        echo '<label class="control-label">'.$model->attributeLabels()['operation_advert_start_time'].'</label>';
        echo DatePicker::widget([
        'name' => 'OperationAdvertContent[operation_advert_start_time]',
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
        'value' => !empty($model->operation_advert_start_time) ? date('Y-m-d', $model->operation_advert_start_time) : '',
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);
    ?>
    <?php
        echo '<label class="control-label">'.$model->attributeLabels()['operation_advert_end_time'].'</label>';
        echo DatePicker::widget([
        'name' => 'OperationAdvertContent[operation_advert_end_time]',
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
        'value' => !empty($model->operation_advert_start_time) ? date('Y-m-d', $model->operation_advert_end_time) : '',
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);
    ?>
    <?php
        echo '<label class="control-label">'.$model->attributeLabels()['operation_advert_online_time'].'</label>';
        echo DatePicker::widget([
        'name' => 'OperationAdvertContent[operation_advert_online_time]',
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
        'value' => !empty($model->operation_advert_start_time) ? date('Y-m-d', $model->operation_advert_online_time) : '',
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);
    ?>
    <?php
        echo '<label class="control-label">'.$model->attributeLabels()['operation_advert_offline_time'].'</label>';
        echo DatePicker::widget([
        'name' => 'OperationAdvertContent[operation_advert_offline_time]',
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
        'value' => !empty($model->operation_advert_start_time) ? date('Y-m-d', $model->operation_advert_offline_time) : '',
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);
    ?>

    <?php //= $form->field($model, 'operation_advert_start_time')->textInput() ?>

    <?php //= $form->field($model, 'operation_advert_end_time')->textInput() ?>

    <?php //= $form->field($model, 'operation_advert_online_time')->textInput() ?>

    <?php //= $form->field($model, 'operation_advert_offline_time')->textInput() ?>

    <?= $form->field($model, 'operation_advert_picture')->fileInput(['maxlength' => true, 'name' => 'operation_advert_picture']) ?>
    <?php echo Html::hiddenInput('old_operation_advert_picture', $model->operation_advert_picture);?>
    <?= !empty($model->operation_advert_picture) ? '<img src="'.$model->operation_advert_picture.'" style="max-width:300px;max-height:300px;">' : '' ;?>
    <?= $form->field($model, 'operation_advert_url')->textInput(['maxlength' => true, 'value' => empty($model->operation_advert_url) ? 'http://' : $model->operation_advert_url]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
