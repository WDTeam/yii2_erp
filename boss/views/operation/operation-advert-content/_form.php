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
    <?= $form->field($model, 'position_id')->dropDownList($positions)->label('选择位置')?>
    <?= $form->field($model, 'operation_advert_content_name')->textInput(['maxlength' => true])?>
    
    <?= $form->field($model, 'operation_advert_picture_text')->fileInput(['maxlength' => true, 'name' => 'operation_advert_picture_text']) ?>

    <?php echo Html::hiddenInput('old_operation_advert_picture_text', $model->operation_advert_picture_text);?>

    <?= !empty($model->operation_advert_picture_text) ? '<img src="'.$model->operation_advert_picture_text.'" style="max-width:300px;max-height:300px;">' : '' ;?>

    <?= $form->field($model, 'operation_advert_url')->textInput(['maxlength' => true, 'value' => empty($model->operation_advert_url) ? 'http://' : $model->operation_advert_url]) ?>

    <div class="form-group">
</div>
        <div class="form-group">
       </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
