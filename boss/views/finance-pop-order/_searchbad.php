<?php
/**
* 提交第三方账单上传解析
* ==========================
* 北京一家洁 版权所有 2015-2018 
* --------------------------------
* 这不是一个自由软件，未经授权不许任何使用和传播。
* ==========================
* @date: 2015-9-25
* @author: peak pan 
* @version:1.0
*/

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\date\DatePicker;
?>

<div class="finance-pop-order-search">
    <?php $form = ActiveForm::begin([
        'action' => ['bad'],
        'method' => 'post',
    ]);
    ?>
  
 <div class='col-md-2'>
    <?= $form->field($model, 'finance_order_channel_id')->widget(Select2::classname(), [
        'name' => '订单渠道',
        'hideSearch' => true,
        'data' => $ordedat,
        'options' => ['placeholder' => '选择订单渠道','class' => 'col-md-2'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
   
    ?>
     </div>
     
     
     <div class='col-md-2'>
    <?= $form->field($model, 'finance_pop_order_customer_tel') ?>
</div>

   <!--  <div class='col-md-2'> 
      $form->field($model, 'finance_order_channel_statuspayment')->widget(DatePicker::classname(), [
    		'name' => 'finance_order_channel_statuspayment',
    		'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    		'pluginOptions' => [
    		'autoclose' => true,
    		'format' => 'yyyy-mm-dd'
    		]
            ]);  
           
     </div>
      <div class='col-md-2'> 
      
     $form->field($model, 'finance_order_channel_endpayment')->widget(DatePicker::classname(), [
    		'name' => 'finance_order_channel_endpayment',
    		'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    		'pluginOptions' => [
    		'autoclose' => true,
    		'format' => 'yyyy-mm-dd'
    		]
            ]);
  </div> -->

     
     
     
    <div class="form-group">
    <div class='col-md-2' style="    margin-top: 22px;">
        <?= Html::submitButton(Yii::t('app', '提交'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', '重置'), ['class' => 'btn btn-default']) ?>
    </div>
</div>
    <?php ActiveForm::end(); ?>

</div>
