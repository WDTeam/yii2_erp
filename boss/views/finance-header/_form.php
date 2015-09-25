<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\Select2;
/**
 * @var yii\web\View $this
 * @var common\models\FinanceHeader $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="finance-header-form">

    <?php 
    $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); 
    echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

//'finance_order_channel_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单渠道id...']], 

//'finance_pay_channel_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付渠道id...']], 

//'create_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...']], 

//'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 0 正常 1 删除...']], 	
	  		
'finance_header_title'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'当前名称...', 'maxlength'=>100]],
    		
/* 'finance_header_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 表头名称...', 'maxlength'=>100]], */   

 'finance_order_channel_name'=>['type'=> Form::INPUT_DROPDOWN_LIST, 'items'=>[1 => '美团', 2 => '大众点评',3=>'京东到家',4=>'淘宝热卖']], 			 		
'finance_pay_channel_name'=>['type'=> Form::INPUT_TEXT,$form->field($model, 'finance_pay_channel_name')->widget(Select2::classname(), [
        'name' => 'finance_pay_channel_name',
        'hideSearch' => true,
        'data' => [1 => '微信', 2 => '支付宝',3=>'财付通',4=>'银联'],
        'options' => ['placeholder' => '选择支付渠道'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]), 'options'=>['placeholder'=>'Enter 支付渠道名称...', 'maxlength'=>100]], 

    'finance_uplod_url'=>['type'=> Form::INPUT_FILE, 'options'=>['placeholder'=>'Enter 上传exl名称...', 'maxlength'=>100]],
    ]
    ]);
    //echo $form->field($model, 'finance_uplod_url')->fileInput();
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>
    

</div>
