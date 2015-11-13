<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\date\DatePicker;
?>

<div class="finance-refund-search" >

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class='col-md-1'>
    <?= $form->field($model, 'finance_refund_code') ?>
	</div> 
    <div class='col-md-1'>
    <?= $form->field($model, 'finance_refund_tel') ?>
	</div> 
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
    <?php echo $form->field($model, 'finance_refund_pay_status')->widget(Select2::classname(), [
            'name' => 'finance_refund_pay_status',
            'hideSearch' => true,
            'data' => [1 => '已支付', 0 => '未支付',2=>'其他'],
            'options' => ['placeholder' => '请选择支付状态', 'inline' => true],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
	</div> 
     <div class='col-md-1'>
    <?= $form->field($model, 'finance_refund_check_name') ?>
	</div>      
    <div class='col-md-2'>
    <?= $form->field($model, 'create_time')->widget(DatePicker::classname(), [
    		'name' => 'create_time',
    		'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    		'pluginOptions' => [
    		'autoclose' => true,
    		'format' => 'yyyy-mm-dd'
    		]
            ]); ?>
      </div>      
  
    <div class="form-group">
     <div class='col-md-2' style="    margin-top: 22px;">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>
		</div>
    <?php ActiveForm::end(); ?>

</div>
