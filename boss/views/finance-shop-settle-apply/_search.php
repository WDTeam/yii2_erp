<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;
?>

<div class="worker-search">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_VERTICAL,
        //'id' => 'login-form-inline',
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    
    <div class='col-md-2'>
    <?php echo  $form->field($model, 'finance_shop_settle_apply_starttime')->widget(DateControl::classname(),[
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
    <div class='col-md-2'>
    <?php echo  $form->field($model, 'finance_shop_settle_apply_endtime')->widget(DateControl::classname(),[
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


    <div class='col-md-2' style="margin-top: 22px;">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        
    </div>

    <?php ActiveForm::end(); ?>
    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_VERTICAL,
        'action' => ['shop-manual-settlement-index'],
        'method' => 'get',
    ]); ?>
    <div class='col-md-1' style="margin-top: 22px;">
        <?= Html::submitButton(Yii::t('app', '人工结算'), ['class' => 'btn btn-default']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
