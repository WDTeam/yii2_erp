<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

?>

<div class="worker-search">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_VERTICAL,
        //'id' => 'login-form-inline',
        'action' => ['query'],
        'method' => 'get',
    ]); ?>
    
    <div class='col-md-3'>
    <?php echo  $form->field($model, 'finance_settle_apply_starttime')->widget(DateControl::classname(),[
        'type' => DateControl::FORMAT_DATE,
        'ajaxConversion'=>false,
        'displayFormat' => 'php:Y-m-d',
        'saveFormat'=>'php:U',
        'options' => [
            'pluginOptions' => [
                 'autoclose' => true
            ]
        ]
    ]);
    
    ?>
  </div>  
    <div class='col-md-3'>
    <?php echo  $form->field($model, 'finance_settle_apply_endtime')->widget(DateControl::classname(),[
        'type' => DateControl::FORMAT_DATE,
        'ajaxConversion'=>false,
        'displayFormat' => 'php:Y-m-d',
        'saveFormat'=>'php:U',
        'options' => [
            'pluginOptions' => [
                 'autoclose' => true
            ]
        ]
    ]);
    
    ?>
  </div> 
    
    <?php ActiveForm::end(); ?>

</div>
