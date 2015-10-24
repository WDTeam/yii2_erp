<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\GeneralPayLog $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="general-pay-log-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'general_pay_log_price'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付金额...', 'maxlength'=>9]], 

'general_pay_log_eo_order_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 第三方订单ID...', 'maxlength'=>30]], 

'general_pay_log_transaction_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 第三方交易流水号...', 'maxlength'=>40]], 

'general_pay_log_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 状态...', 'maxlength'=>30]], 

'general_pay_log_json_aggregation'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter 记录数据集合...','rows'=> 6]], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...', 'maxlength'=>10]], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 更新时间...', 'maxlength'=>10]], 

'pay_channel_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付渠道...']], 

'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 删除...']], 

'general_pay_log_shop_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 商品名称...', 'maxlength'=>50]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
