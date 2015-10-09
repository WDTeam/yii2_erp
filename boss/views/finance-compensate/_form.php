<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\FinanceCompensate $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-compensate-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'finance_compensate_pay_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付金额...', 'maxlength'=>8]], 

'finance_compensate_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 赔偿金额...', 'maxlength'=>8]], 

'finance_compensate_discount'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠价格...', 'maxlength'=>6]], 

'finance_pay_channel_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付渠道id...']], 

'finance_order_channel_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单渠道id...']], 

'finance_compensate_pay_create_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单支付时间...']], 

'finance_compensate_pay_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付状态 1支付 0 未支付 2 其他...']], 

'finance_compensate_worker_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务阿姨...']], 

'create_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 退款申请时间...']], 

'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 0 正常 1删除...']], 

'finance_compensate_oa_num'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter OA批号...', 'maxlength'=>40]], 

'finance_compensate_cause'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 赔偿原因...', 'maxlength'=>150]], 

'finance_compensate_tel'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 用户电话...', 'maxlength'=>20]], 

'finance_compensate_worker_tel'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨电话...', 'maxlength'=>20]], 

'finance_compensate_proposer'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 申请人...', 'maxlength'=>20]], 

'finance_compensate_auditor'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 审核人...', 'maxlength'=>20]], 

'finance_pay_channel_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付渠道名称...', 'maxlength'=>60]], 

'finance_order_channel_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单渠道名称...', 'maxlength'=>60]], 

'finance_compensate_pay_flow_num'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单号...', 'maxlength'=>80]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
