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

    <?php
        $form = ActiveForm::begin([
            'type'=>ActiveForm::TYPE_HORIZONTAL,
            'enableAjaxValidation' => true,
            'validationUrl'=>Yii::$app->urlManager->createUrl([
                'operation/operation-spec/ajax-validate-spec-info',
                'action' => $action,
                'id' => $model->id
            ])
        ]); 
    ?>

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
                    'operation_spec_name'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'规格名称...','maxlength'=> 20]],

                    'operation_spec_description'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'规格备注...','rows'=> 6]],

                    'operation_spec_values'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>"规格属性【每项数据之间用逗号';'做分割】...",'rows'=> 6]],
                    'operation_spec_strategy_unit'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'计量单位...','maxlength'=> 10]],

                ]
            ]); ?>

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
