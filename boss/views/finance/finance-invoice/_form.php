<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var dbbase\models\FinanceInvoice $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-invoice-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'finance_invoice_serial_number'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 流水号...']], 

'pay_channel_pay_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付方式...']], 

'finance_invoice_pay_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付状态...']], 

'admin_confirm_uid'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 确认人...']], 

'finance_invoice_enrolment_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 申请时间...']], 

'finance_invoice_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 0 未开发票 1已邮寄 2 未邮寄  3 上门取  4 审核中 5 审核通过 6已完成 7 已退回...']], 

'finance_invoice_check_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 审核人id...']], 

'finance_invoice_number'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 发票数量...']], 

'finance_invoice_district_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 城市id...']], 

'classify_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 业务id...']], 

'classify_title'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 业务title...']], 

'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 0 正常 1 删除...']], 

'finance_invoice_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 发票金额...', 'maxlength'=>8]], 

'finance_invoice_service_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 开发票服务费...', 'maxlength'=>8]], 

'create_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 增加时间...', 'maxlength'=>10]], 

'finance_invoice_customer_tel'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 用户电话...', 'maxlength'=>20]], 

'finance_invoice_worker_tel'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨电话...', 'maxlength'=>20]], 

'pay_channel_pay_title'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付名称...', 'maxlength'=>200]], 

'finance_invoice_address'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 邮寄地址...', 'maxlength'=>200]], 

'finance_invoice_corp_address'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 公司地址...', 'maxlength'=>200]], 

'finance_invoice_title'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 发票抬头...', 'maxlength'=>100]], 

'finance_invoice_corp_email'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 邮箱...', 'maxlength'=>40]], 

'finance_invoice_corp_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 公司名称...', 'maxlength'=>150]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
