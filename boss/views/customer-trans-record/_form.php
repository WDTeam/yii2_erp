<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\CustomerTransRecord $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="customer-trans-record-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter ID...']], 

'customer_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 用户ID...', 'maxlength'=>11]], 

'order_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单ID...', 'maxlength'=>11]], 

'order_channel_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单渠道...']], 

'customer_trans_record_order_channel'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单渠道名称...']], 

'pay_channel_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付渠道...']], 

'customer_trans_record_pay_channel'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付渠道名称...']], 

'customer_trans_record_mode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 交易方式:1消费,2=充值,3=退款,4=补偿...']], 

'customer_trans_record_mode_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 交易方式名称...']], 

'customer_trans_record_promo_code_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠码金额...', 'maxlength'=>5]], 

'customer_trans_record_coupon_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券金额...', 'maxlength'=>5]], 

'customer_trans_record_cash'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 现金支付...', 'maxlength'=>5]], 

'customer_trans_record_pre_pay'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 预付费金额（第三方）...', 'maxlength'=>5]], 

'customer_trans_record_online_pay'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 在线支付...', 'maxlength'=>5]], 

'customer_trans_record_online_balance_pay'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 在线余额支付...', 'maxlength'=>5]], 

'customer_trans_record_online_service_card_on'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务卡号...', 'maxlength'=>30]], 

'customer_trans_record_online_service_card_pay'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务卡支付...', 'maxlength'=>5]], 

'customer_trans_record_online_service_card_current_balance'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务卡当前余额...', 'maxlength'=>8]], 

'customer_trans_record_online_service_card_befor_balance'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务卡之前余额...', 'maxlength'=>8]], 

'customer_trans_record_compensate_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 补偿金额...', 'maxlength'=>8]], 

'customer_trans_record_refund_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 退款金额...', 'maxlength'=>8]], 

'customer_trans_record_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 余额支付...', 'maxlength'=>8]], 

'customer_trans_record_order_total_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单总额...', 'maxlength'=>5]], 

'customer_trans_record_total_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 交易总额...', 'maxlength'=>9]], 

'customer_trans_record_current_balance'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 当前余额...', 'maxlength'=>8]], 

'customer_trans_record_befor_balance'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 之前余额...', 'maxlength'=>8]], 

'customer_trans_record_transaction_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 交易流水号...', 'maxlength'=>40]], 

'customer_trans_record_remark'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 备注...', 'maxlength'=>255]], 

'customer_trans_record_verify'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 验证...', 'maxlength'=>32]], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...', 'maxlength'=>10]], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 更新时间...', 'maxlength'=>10]], 

'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 删除...']], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
