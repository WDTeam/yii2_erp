<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\operation\OperationServerCardOrder $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-server-card-order-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'usere_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 用户id...', 'maxlength'=>20]], 

'order_customer_phone'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 用户手机号...']], 

'server_card_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 卡id...', 'maxlength'=>20]], 

'card_type'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 卡类型...']], 

'card_level'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 卡级别...']], 

'order_src_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单来源id...']], 

'order_channel_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单渠道id...']], 

'order_lock_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 是否锁定...']], 

'order_status_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单状态id...']], 

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

'order_pay_type'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付方式...']], 

'pay_channel_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付渠道id...']], 

'paid_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付时间...']], 

'par_value'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 卡面值...', 'maxlength'=>8]], 

'reb_value'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 卡优惠值...', 'maxlength'=>8]], 

'order_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单金额...', 'maxlength'=>8]], 

'order_pay_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付金额...', 'maxlength'=>8]], 

'order_code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单号...', 'maxlength'=>20]], 

'card_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 卡名...', 'maxlength'=>64]], 

'order_src_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单来源名称...', 'maxlength'=>64]], 

'order_channel_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单渠道名称...', 'maxlength'=>64]], 

'order_status_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单状态名称...', 'maxlength'=>64]], 

'pay_channel_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付渠道名称...', 'maxlength'=>64]], 

'order_pay_flow_num'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付流水号...', 'maxlength'=>255]], 

    ]


    ]);?>
    <div class="form-group">
        <div class="col-sm-offset-0 col-sm-12">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-lg btn-block' : 'btn btn-primary btn-lg btn-block']);?>
        </div>
    </div>
    <? ActiveForm::end(); ?>

</div>
