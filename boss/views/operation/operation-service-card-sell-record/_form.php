<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\OperationServiceCardSellRecord $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-service-card-sell-record-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'customer_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 用户id...', 'maxlength'=>20]], 

'customer_phone'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 用户手机号...']], 

'service_card_info_card_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务卡id...', 'maxlength'=>20]], 

'service_card_sell_record_channel_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 购卡订单渠道id...']], 

'service_card_sell_record_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 购卡订单状态...']], 

'customer_trans_record_pay_mode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付方式...']], 

'pay_channel_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付渠道id...']], 

'customer_trans_record_pay_account'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付帐号...']], 

'customer_trans_record_paid_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付时间...']], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单创建时间...']], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单更改时间...']], 

'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 状态...']], 

'service_card_sell_record_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 购卡订单金额...', 'maxlength'=>8]], 

'customer_trans_record_pay_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付金额...', 'maxlength'=>8]], 

'service_card_sell_record_code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 购卡订单号...', 'maxlength'=>20]], 

'service_card_info_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务卡名...', 'maxlength'=>64]], 

'service_card_sell_record_channel_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 购卡订单渠道名称...', 'maxlength'=>64]], 

'customer_trans_record_pay_channel'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付渠道名称...', 'maxlength'=>64]], 

'customer_trans_record_transaction_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付流水号...', 'maxlength'=>255]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
