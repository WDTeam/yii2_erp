<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\FinancePopOrder $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-pop-order-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'finance_order_channel_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 下单渠道(对应finance_order_channel)...']], 

'finance_order_channel_title'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 下单渠道名称(对应order_channel)...', 'maxlength'=>80]], 

'finance_pay_channel_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付渠道(对应pay_channel)...']], 

'finance_pay_channel_title'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付渠道名称(对应pay_channel)...', 'maxlength'=>80]], 

'finance_pop_order_worker_uid'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务阿姨...']], 

'finance_pop_order_booked_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 预约开始时间...']], 

'finance_pop_order_booked_counttime'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 预约服务时长(按分钟记录)...']], 

'finance_pop_order_coupon_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠卷id...']], 

'finance_pop_order_order_type'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单类型...']], 

'finance_pop_order_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付状态...']], 

'finance_pop_order_finance_isok'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 财务确定...']], 

'finance_pop_order_order_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 下单时间...']], 

'finance_pop_order_pay_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付时间...']], 

'finance_pop_order_pay_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 1 对账成功 2 财务确定ok  3 财务确定on 4 财务未处理...']], 

'finance_pop_order_check_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 操作人id...']], 

'finance_pop_order_finance_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 财务对账提交时间...']], 

'create_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 增加时间...']], 

'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 0 正常 1 删除...']], 

'finance_pop_order_sum_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 总金额...', 'maxlength'=>8]], 

'finance_pop_order_coupon_count'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠卷金额...', 'maxlength'=>6]], 

'finance_pop_order_discount_pay'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠金额...', 'maxlength'=>8]], 

'finance_pop_order_reality_pay'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 实际收款...', 'maxlength'=>8]], 

'finance_pop_order_number'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 第三方订单号...', 'maxlength'=>40]], 

'finance_pop_order_order2'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 子订单号...', 'maxlength'=>40]], 

'finance_pop_order_channel_order'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 获取渠道唯一订单号...', 'maxlength'=>40]], 

'finance_pop_order_customer_tel'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 用户电话...', 'maxlength'=>20]], 

'finance_pop_order_pay_title'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 状态 描述...', 'maxlength'=>30]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
