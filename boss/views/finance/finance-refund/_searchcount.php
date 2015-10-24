<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\date\DatePicker;
use boss\components\AreaCascade;


?>

<div class="finance-refund-search" >

    <?php $form = ActiveForm::begin([
        'action' => ['countinfo'],
        'method' => 'get',
    ]); ?>
 
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
  
  
  <div class="operation-city-form">
            <?=AreaCascade::widget([
                'model' => $model,
                'options' => ['class' => 'form-control inline'],
                'label' =>'城市',
                'grades' => 'county',
            ]);
            ?>
            </div>
     
     
   <div class='col-md-2'>
 <?php echo $form->field($model, 'finance_refund_shop_id')->widget(Select2::classname(), [
            'name' => 'finance_refund_shop_id',
            'hideSearch' => true,
            'data' => [1 => '北京市区', 0 => '天津门店',2=>'大大门店'],
            'options' => ['placeholder' => '请选择门店', 'inline' => true],
            'pluginOptions' => [
                'allowClear' => true
            ],
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
