<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
/**
 * @var yii\web\View $this
 * @var dbbase\models\FinanceHeader $model
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
  		
'finance_header_title'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'当前名称...', 'maxlength'=>100],'class' => 'col-md-2'],
  'finance_order_channel_name'=>['type'=> Form::INPUT_DROPDOWN_LIST, 'items'=>\core\models\operation\OperationOrderChannel::getorderchannellist('all'),'options' => [
     		'prompt' => '请选择下单渠道',
     		],'class' => 'col-md-2'],
    		'finance_pay_channel_name'=>['type'=> Form::INPUT_DROPDOWN_LIST, 'items'=>\core\models\operation\OperationPayChannel::getpaychannellist('all'),'options' => [
    		'prompt' => '请选择支付渠道',
    		],'class' => 'col-md-2'],	
 'finance_uplod_url'=>['type'=> Form::INPUT_FILE, 'options'=>['placeholder'=>'上传exl名称...', 'maxlength'=>100],'class' => 'col-md-2'],
    ]
    ]);
   
    //echo $form->field($model, 'finance_uplod_url')->fileInput();
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>
    

</div>
