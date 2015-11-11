<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\OperationServiceCardWithCustomer $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-service-card-with-customer-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'service_card_sell_record_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 购卡销售记录id...', 'maxlength'=>20]], 

'service_card_info_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务卡信息id...', 'maxlength'=>20]], 

'customer_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 持卡人id...']], 

'service_card_info_scope'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 使用范围...']], 

'service_card_with_customer_buy_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 购买日期...']], 

'service_card_with_customer_valid_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 有效截止日期...']], 

'service_card_with_customer_activated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 激活日期...']], 

'service_card_with_customer_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务卡状态...']], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...']], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 更改时间...']], 

'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 状态...']], 

'customer_trans_record_pay_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 实收金额...', 'maxlength'=>8]], 

'service_card_info_value'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 卡面金额...', 'maxlength'=>8]], 

'service_card_info_rebate_value'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠金额...', 'maxlength'=>8]], 

'service_card_with_customer_balance'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 余额...', 'maxlength'=>8]], 

'service_card_sell_record_code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 购卡订单号...', 'maxlength'=>64]], 

'service_card_with_customer_code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务卡号...', 'maxlength'=>64]], 

'service_card_info_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务卡卡名...', 'maxlength'=>64]], 

'customer_phone'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 持卡人手机号...', 'maxlength'=>11]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
