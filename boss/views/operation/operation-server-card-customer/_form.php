<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\operation\OperationServerCardCustomer $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-server-card-customer-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'order_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单id...', 'maxlength'=>20]], 

'card_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 卡信息id...', 'maxlength'=>20]], 

'card_type'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 卡类型...']], 

'card_level'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 卡级别...']], 

'customer_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 持卡人id...']], 

'use_scope'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 使用范围...']], 

'buy_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 购买日期...']], 

'valid_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 有效截止日期...']], 

'activated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 激活日期...']], 

'freeze_flag'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 冻结标识...']], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...']], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 更改时间...']], 

'pay_value'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 实收金额...', 'maxlength'=>8]], 

'par_value'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 卡面金额...', 'maxlength'=>8]], 

'reb_value'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠金额...', 'maxlength'=>8]], 

'res_value'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 余额...', 'maxlength'=>8]], 

'order_code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单编号...', 'maxlength'=>64]], 

'card_no'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 卡号...', 'maxlength'=>64]], 

'card_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 卡名...', 'maxlength'=>64]], 

'customer_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 持卡人名称...', 'maxlength'=>16]], 

'customer_phone'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 持卡人手机号...', 'maxlength'=>11]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
