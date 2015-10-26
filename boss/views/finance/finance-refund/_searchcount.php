<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\date\DatePicker;
use boss\components\AreaCascade;
use yii\web\JsExpression;
?>

<div class="finance-refund-search" >

    <?php $form = ActiveForm::begin([
        'action' => ['countinfo'],
        'method' => 'get',
    ]); ?>
 
    <div class='col-md-3'>
    <?= $form->field($model, 'create_time')->widget(DatePicker::classname(), [
    		'name' => 'create_time',
    		'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    		'pluginOptions' => [
    		'autoclose' => true,
    		'format' => 'yyyy-mm-dd'
    		]
            ]); ?>
      </div>      
  
  
    <div class='col-md-3'>
    <?= $form->field($model, 'create_time_end')->widget(DatePicker::classname(), [
    		'name' => 'create_time_end',
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
     
     
      <div class='col-md-3'>
        <?= $form->field($model, 'finance_refund_shop_id')->widget(Select2::classname(), [
            'initValueText' => '门店名称', // set the initial display text
            'options' => ['placeholder' => '搜索门店名称...', 'class' => 'col-md-2'],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 0,
                'ajax' => [
                    'url' => \yii\helpers\Url::to(['show-shop']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(city) { return city.text; }'),
                'templateSelection' => new JsExpression('function (city) { return city.text; }'),
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
