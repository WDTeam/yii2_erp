<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use dosamigos\datetimepicker\DateTimePicker;
use boss\components\GoodsTypeCascade;
use kartik\widgets\FileInput;

use boss\models\operation\OperationCommon;

/**
 * @var yii\web\View $this
 * @var boss\models\Operation\OperationGoods $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-goods-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL, 'options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">基础信息</h3>
        </div>
        <div class="panel-body">
            <?php
            echo Form::widget([

            'model' => $model,
            'form' => $form,
            'columns' => 1,
            'attributes' => [
                'selected_service_scene'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'场景名称...', 'maxlength'=>32]],

                'selected_service_area'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'请输入区域...', 'maxlength'=>32]],
                'selected_service_sub_area'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'请输入子区域...', 'maxlength'=>64]],
                'selected_service_standard'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'请输入清洁标准...', 'maxlength'=>128]],
                //'selected_service_price'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'请输入价格...', 'maxlength'=>64]],
                'selected_service_unit'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'请输入时间...', 'maxlength'=>64]],

                ]]); ?>
            <?= $form->field($model, 'selected_service_area_standard')->dropDownList(['1' => '小于100平米', 2 => '大于100平米'], ['prompt' => '请选择分类'])->label('选择面积分类') ?>
            <?= $form->field($model, 'selected_service_photo')->widget(FileInput::classname(), [
                'options' => ['multiple' => true],
                'pluginOptions' => [
                    'previewFileType' => 'any',
                    'showPreview' => true,
                    'showCaption' => false,
                    'showRemove' => true,
                    'showUpload' => false,
                    'initialPreview'=>[
                        OperationCommon::getPhotoShow($model->selected_service_photo)
                    ],
                ]
            ])?>

        </div>
        <div class="panel-footer">
            <div class="form-group">
                <div class="col-sm-offset-0 col-sm-12">
                    <?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-lg btn-block' : 'btn btn-primary btn-lg btn-block']); ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
