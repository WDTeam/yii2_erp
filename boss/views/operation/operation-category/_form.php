<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\FileInput;

/**
 * @var yii\web\View $this
 * @var dbbase\models\OperationCategory $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-category-form">

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
                'operation_category_name' => [
                    'type'=> Form::INPUT_TEXT,
                    'options'=>[
                        'placeholder'=>'输入服务品类名称...',
                        'maxlength'=>30
                    ]
                ],
                'operation_category_price_description' => [
                    'type' => Form::INPUT_TEXT,
                    'options' => [
                        'placeholder' => '输入服务品类价格备注...',
                        'maxlength'=>30
                    ]
                ],
                'operation_category_url' => [
                    'type' => Form::INPUT_TEXT,
                    'options' => [
                        'placeholder' => '输入服务品类跳转地址...',
                        'maxlength'=>50
                    ]
                ],
                'operation_category_introduction' => [
                    'type' => Form::INPUT_TEXTAREA,
                    'options' => [
                        'placeholder'=>'输入服务品类简介...',
                        'rows'=> 4
                    ]
                ],
            ]

            ]); ?>
            <?= $form->field($model, 'operation_category_icon')->widget(FileInput::classname(), [
                'options' => ['multiple' => true],
                'pluginOptions' => [
                    'previewFileType' => 'any',
                    'showPreview' => true,
                    'showCaption' => false,
                    'showRemove' => true,
                    'showUpload' => false,
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
