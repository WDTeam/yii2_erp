<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\GeneralPay $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="general-pay-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'customer_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 用户ID...', 'maxlength'=>11]], 

'general_pay_source_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 数据来源名称...', 'maxlength'=>20]], 

'order_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单ID...', 'maxlength'=>11]], 

'general_pay_source'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 数据来源:1=APP微信,2=H5微信,3=APP百度钱包,4=APP银联,5=APP支付宝,6=WEB支付宝,7=HT淘宝,8=H5百度直达号,9=HT刷卡,10=HT现金,11=HT刷卡...']], 

'general_pay_mode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 交易方式:1=充值,2=余额支付,3=在线支付,4=退款,5=赔偿...']], 

'general_pay_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 状态：0=失败,1=成功...']], 

'general_pay_is_coupon'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 是否返券...']], 

'admin_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 管理员ID...', 'maxlength'=>10]], 

'worker_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 销售卡阿姨ID...', 'maxlength'=>10]], 

'handle_admin_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 办卡人ID...', 'maxlength'=>10]], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...', 'maxlength'=>10]], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 更新时间...', 'maxlength'=>10]], 

'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 删除...']], 

'general_pay_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 发起充值/交易金额...', 'maxlength'=>8]], 

'general_pay_actual_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 实际充值/交易金额...', 'maxlength'=>8]], 

'general_pay_transaction_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 第三方交易流水号...', 'maxlength'=>40]], 

'general_pay_eo_order_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 商户ID(第三方交易)...', 'maxlength'=>30]], 

'general_pay_admin_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 管理员名称...', 'maxlength'=>30]], 

'general_pay_handle_admin_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 办卡人名称...', 'maxlength'=>30]], 

'general_pay_memo'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 备注...', 'maxlength'=>255]], 

'general_pay_verify'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付验证...', 'maxlength'=>32]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
