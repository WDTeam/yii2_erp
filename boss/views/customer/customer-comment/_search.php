<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use boss\components\AreaCascade;
use kartik\date\DatePicker;

use yii\web\JsExpression;
use core\models\order\Order;


/**
 * @var yii\web\View $this
 * @var boss\models\CustomerCommentSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="customer-comment-search">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_VERTICAL,
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    
 <!--    operation_shop_district_id  商圈id


worker_tel

worker_id
 -->


    
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
            <?= $form->field($model, 'operation_shop_district_id')->widget(Select2::classname(), [
            'name' => 'worker_district',
            'hideSearch' => true,
            'data' => Order::getDistrictList(),
            'options' => ['placeholder' => '选择商圈', 'class' => 'm_ipu'],
            'pluginOptions' => [
                'tags' => true,
                'maximumInputLength' => 10
            ],
        ])->label('商圈', ['class' => 'm_ipone']); ?>  
        </div>
        
           
           <div class='col-md-3'>
    <?= $form->field($model, 'created_at')->widget(DatePicker::classname(), [
    		'name' => 'create_time',
    		'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    		'pluginOptions' => [
    		'autoclose' => true,
    		'format' => 'yyyy-mm-dd'
    		]
            ]); ?>
      </div>      
  
  
    <div class='col-md-3'>
    <?= $form->field($model, 'created_at_end')->widget(DatePicker::classname(), [
    		'name' => 'create_time_end',
    		'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    		'pluginOptions' => [
    		'autoclose' => true,
    		'format' => 'yyyy-mm-dd'
    		]
            ]); ?>
            
      </div>
      
      <div class='col-md-2'>
        <?php echo $form->field($model, 'customer_comment_level')->widget(Select2::classname(), [
            'name' => 'customer_comment_level',
            'hideSearch' => true,
            'data' => [1 => '满意', 2 => '一般',3=> '不满意'],
            'options' => ['placeholder' => '选择评价等级', 'inline' => true],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
    </div>
      
    <div class='col-md-2'>
        <?php echo $form->field($model, 'worker_tel')->label('阿姨手机'); ?>
    </div>
    <div class="form-group">
        <div class='col-md-2' style="margin-top: 22px;">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
