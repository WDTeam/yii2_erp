<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var boss\models\Operation\OperationSpec $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-spec-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); ?>
<!-- <?//= $form->field($model, 'operation_spec_name')->textInput(['maxlength' => true]) ?> -->
    <?php
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'operation_spec_name'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'规格名称...','maxlength'=> true]],

            'operation_spec_description'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'规格备注...','rows'=> 6]],

            'operation_spec_values'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>"规格属性【每项数据之间用逗号';'做分割】...",'rows'=> 6]],
            'operation_spec_strategy_unit'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'计量单位...','maxlength'=> true]],

//            'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...']],
    //
    //        'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 编辑时间...']],
    //
    //        'operation_spec_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 规格名称...', 'maxlength'=>60]],

        ]
    ]); ?>
<!--    <div class = "bs-glyphicons">-->
<!--        <ul class = "bs-glyphicons-list">-->
<!--            <li id = "addSpecValue">-->
<!--                <span aria-hidden="true" class="glyphicon glyphicon-plus"></span>-->
<!--                <span class="glyphicon-class">增加规格属性</span>-->
<!--            </li>-->
<!--        </ul>-->
<!--    </div>-->
    <?php
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
