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
            <?= $form->field($model, 'operation_category_id')->dropDownList($OperationCategory, ['prompt' => '请选择分类'])->label('选择服务类型') ?>
            <?php
            echo Form::widget([

            'model' => $model,
            'form' => $form,
            'columns' => 1,
            'attributes' => [
                'operation_goods_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'服务项目名称...', 'maxlength'=>20]],

                'operation_goods_introduction'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter 服务类型简介...', 'rows'=> 6, 'maxlength'=>100]],

                'operation_goods_english_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务类型英文名称...', 'maxlength'=>100]],


                ]]); ?>

                <?php echo $form->field($model, 'operation_spec_info')->dropDownList($OperationSpec, ['prompt' => '请选择规格'])->label('选择规格') ?>

                <?= $form->field($model, 'operation_goods_service_interval_time')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'operation_goods_service_estimate_time')->textInput(['maxlength' => 4]) ?>


                <?= $form->field($model, 'operation_goods_price_description')->textInput(['maxlength' => 40]) ?>

                <?= $form->field($model, 'operation_tags')->textInput(['maxlength' => true, 'placeholder' => '每个标签不超过12个字并用分号隔开']) ?>
                <?= $form->field($model, 'operation_goods_img')->widget(FileInput::classname(), [
                    'options' => ['multiple' => true],
                    'pluginOptions' => [
                        'previewFileType' => 'any',
                        'showPreview' => true,
                        'showCaption' => false,
                        'showRemove' => true,
                        'showUpload' => false,
                        'initialPreview'=>[
                            OperationCommon::getPhotoShow($model->operation_goods_img)
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
