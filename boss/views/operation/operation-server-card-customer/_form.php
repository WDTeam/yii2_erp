<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\operation\ServerCardCustomer $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="server-card-customer-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'order_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单id...', 'maxlength'=>20]], 

'card_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 卡信息id...', 'maxlength'=>20]], 

'card_type'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 卡类型...']], 

'card_level'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 卡级别...']], 

'customer_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 持卡人id...']], 

'use_scope'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 使用范围...']], 
'buy_at'=>[
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
'valid_at'=>[
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
'activated_at'=>[
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

'freeze_flag'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 冻结标识...']], 

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

'pay_value'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 实收金额...', 'maxlength'=>8]], 

'par_value'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 卡面金额...', 'maxlength'=>8]], 

'reb_value'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠金额...', 'maxlength'=>8]], 

'res_value'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 余额...', 'maxlength'=>8]], 

'order_code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单编号...', 'maxlength'=>64]], 

'card_no'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 卡号...', 'maxlength'=>64]], 

'card_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 卡名...', 'maxlength'=>64]], 

'customer_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 持卡人名称...', 'maxlength'=>16]], 

'customer_phone'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 持卡人手机号...', 'maxlength'=>11]], 

    ]


    ]);?>
    <div class="form-group">
        <div class="col-sm-offset-0 col-sm-12">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-lg btn-block' : 'btn btn-primary btn-lg btn-block']);?>
        </div>
    </div>
    <? ActiveForm::end(); ?>

</div>
