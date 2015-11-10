<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use core\models\system\SystemUser;

use kartik\widgets\Select2;
use yii\web\JsExpression;

/**
 * @var yii\web\View $this
 * @var dbbase\models\shop\ShopCustomeRelation $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="shop-custome-relation-form">
    <?php /* $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);

     echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
    		'system_user_id'=>['type'=> Form::INPUT_DROPDOWN_LIST, 'items'=>SystemUser::getuserlist(),'options' => [
    		'prompt' => '请选择用户',
    		],'class' => 'col-md-2'],
    		
    		
    		'shopid'=>['type'=> Form::INPUT_DROPDOWN_LIST, 'items'=>SystemUser::getuserlist(),'options' => [
    		'prompt' => '请选择门店',
    		],'class' => 'col-md-2'],
    		
    		
    		
    		'shop_manager_id'=>['type'=> Form::INPUT_DROPDOWN_LIST, 'items'=>SystemUser::getuserlist(),'options' => [
    		'prompt' => '请选择家政公司',
    		],'class' => 'col-md-2'],

    ]


    ]); */ ?>
    
    
     
     <?php $form = ActiveForm::begin([
     		'action' => ['add-create'],
     		'method' => 'post',
         ]); ?>
     
     
      <?= $form->field($model, 'system_user_id')->widget(Select2::classname(), [
        'name' => '用户',
        'hideSearch' => false,
        'data' => SystemUser::getuserlist(),
        'options' => ['placeholder' => '请选择用户','class' => 'col-md-2'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
   
    ?>
     
     
     
      <?= $form->field($model, 'shop_manager_id')->widget(Select2::classname(), [
            'initValueText' => '家政公司',
            'options' => ['placeholder' => '搜索家政公司名称...', 'class' => 'col-md-2'],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 0,
                'ajax' => [
                    'url' => \yii\helpers\Url::to(['shop-manager']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(city) { return city.text; }'),
                'templateSelection' => new JsExpression('function (city) { return city.text; }'),
            ],
        ]); ?>
        
        
      
    <?= $form->field($model, 'shopid')->widget(Select2::classname(), [
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
              
         
       
       
          
              
   <?php  echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
