<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\operation\OperationServerCardRecord $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-server-card-record-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'trade_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 交易id...', 'maxlength'=>20]], 

'cus_card_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 客户服务卡...', 'maxlength'=>20]], 

'created_at'=>[
                'type'=> Form::INPUT_WIDGET, 
                'widgetClass'=>DateControl::classname(),
                'options' => [
                    'type'=>DateControl::FORMAT_DATE,
                    'ajaxConversion'=>false,
                    'displayFormat' => 'php:Y-m-d',
                    'saveFormat'=>'php:U',
                    'options' => [
                        'pluginOptions' => [
                            'autoclose' => true
                        ]
                    ]
                ]
            ], 
'updated_at'=>[
                'type'=> Form::INPUT_WIDGET, 
                'widgetClass'=>DateControl::classname(),
                'options' => [
                    'type'=>DateControl::FORMAT_DATE,
                    'ajaxConversion'=>false,
                    'displayFormat' => 'php:Y-m-d',
                    'saveFormat'=>'php:U',
                    'options' => [
                        'pluginOptions' => [
                            'autoclose' => true
                        ]
                    ]
                ]
            ], 
'front_value'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 使用前金额...', 'maxlength'=>8]], 

'behind_value'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 使用后金额...', 'maxlength'=>8]], 

'use_value'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 使用金额...', 'maxlength'=>8]], 

    ]


    ]);?>
    <div class="form-group">
        <div class="col-sm-offset-0 col-sm-12">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-lg btn-block' : 'btn btn-primary btn-lg btn-block']);?>
        </div>
    </div>
    <? ActiveForm::end(); ?>

</div>
