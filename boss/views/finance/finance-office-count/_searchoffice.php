<?php
/**
* 查询日派单管理
* ==========================
* 北京一家洁 版权所有 2015-2018 
* ----------------------------
* 这不是一个自由软件，未经授权不许任何使用和传播。
* ==========================
* @date: 2015-11-3
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
        'action' => ['indexoffice'],
        'method' => 'get',
    ]);
    ?>
  
  
   <div class='col-md-2'>
    <?= $form->field($model, 'stypechannel')->widget(Select2::classname(), [
        'name' => '渠道选择',
        'hideSearch' => true,		
        'data' => ['1'=>'全部','2'=>'三方渠道','3'=>'自营渠道'],
        'options' => ['placeholder' => '请选择渠道选择','class' => 'col-md-2'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
   
    ?>
   </div>
 	<div class='col-md-2'>
    <?= $form->field($model, 'channel_id')->widget(Select2::classname(), [
        'name' => '订单渠道',
        'hideSearch' => true,
        'data' => \core\models\operation\OperationOrderChannel::getorderchannellist('all'),
        'options' => ['placeholder' => '选择订单渠道','class' => 'col-md-2'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
   
    ?>
     </div>
     
     
      <div class='col-md-2'>
    <?= $form->field($model, 'pay_channel_id')->widget(Select2::classname(), [
        'name' => '支付渠道',
        'hideSearch' => true,		
        'data' => \core\models\operation\OperationPayChannel::getpaychannellist('all'),
        'options' => ['placeholder' => '选择处理状态','class' => 'col-md-2'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
   
    ?>
     </div>
     
     
      <div class='col-md-2'>
    <?= $form->field($model, 'created_at')->widget(DatePicker::classname(), [
    		'name' => 'created_at',
    		'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    		'pluginOptions' => [
    		'autoclose' => true,
    		'format' => 'yyyy-mm-dd'
    		]
            ]);  ?>
           
     </div>
      <div class='col-md-2'>
      
     <?= $form->field($model, 'created_at_end')->widget(DatePicker::classname(), [
    		'name' => 'created_at_end',
    		'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    		'pluginOptions' => [
    		'autoclose' => true,
    		'format' => 'yyyy-mm-dd'
    		]
            ]); ?>
     </div>
     
     
      <div class='col-md-2'>
    <?= $form->field($model, 'order_code') ?>
</div>
     
    <div class="form-group">
    <div class='col-md-2' style="    margin-top: 22px;">
        <?= Html::submitButton(Yii::t('app', '提交'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', '重置'), ['class' => 'btn btn-default']) ?>
    </div>
</div>
    <?php ActiveForm::end(); ?>

</div>
