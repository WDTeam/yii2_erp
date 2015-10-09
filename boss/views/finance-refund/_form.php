<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\FinanceRefund $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-refund-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'finance_refund_tel'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 用户电话...', 'maxlength'=>20]], 

'finance_refund_stype'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 申请方式...']], 

'create_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 退款申请时间...']], 

'finance_refund_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 退款金额...', 'maxlength'=>8]], 

'finance_refund_discount'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠价格...', 'maxlength'=>6]], 

'finance_refund_pay_create_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单支付时间...']], 

'finance_pay_channel_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付方式id...']], 

'finance_refund_pay_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付状态 1支付 0 未支付 2 其他...']], 

'finance_refund_worker_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务阿姨...']], 

'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 0 正常 1删除...']], 

'finance_refund_worker_tel'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨电话...', 'maxlength'=>20]], 

'finance_refund_reason'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 退款理由...', 'maxlength'=>255]], 

'finance_pay_channel_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付方式名称...', 'maxlength'=>80]], 

'finance_refund_pay_flow_num'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单号...', 'maxlength'=>80]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
