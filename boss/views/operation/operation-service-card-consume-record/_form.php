<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\OperationServiceCardConsumeRecord $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-service-card-consume-record-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'customer_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 用户id...', 'maxlength'=>20]], 

'customer_trans_record_transaction_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务交易流水...', 'maxlength'=>20]], 

'order_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务订单id...', 'maxlength'=>20]], 

'service_card_with_customer_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务卡id...', 'maxlength'=>20]], 

'service_card_consume_record_consume_type'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务类型...']], 

'service_card_consume_record_business_type'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 业务类型...']], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...']], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 更改时间...']], 

'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 状态...']], 

'service_card_consume_record_front_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 使用前金额...', 'maxlength'=>8]], 

'service_card_consume_record_behind_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 使用后金额...', 'maxlength'=>8]], 

'service_card_consume_record_use_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 使用金额...', 'maxlength'=>8]], 

'order_code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务订单号...', 'maxlength'=>20]], 

'service_card_with_customer_code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务卡号...', 'maxlength'=>64]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
