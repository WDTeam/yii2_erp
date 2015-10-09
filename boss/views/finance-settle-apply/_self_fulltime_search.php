<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

?>

<div class="worker-search">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_VERTICAL,
        //'id' => 'login-form-inline',
        'action' => ['self-fulltime-worker-settle-index'],
        'method' => 'get',
    ]); ?>
    
    <div class='col-md-2'>
    <?php echo  $form->field($model, 'settleMonth')->widget(DateControl::classname(),[
        'type' => DateControl::FORMAT_DATE,
        'value'=>$model->settleMonth,
        'ajaxConversion'=>false,
        'displayFormat' => 'php:Y-m',
        'saveFormat'=>'php:Y-m',
        'options' => [
            'pluginOptions' => [
                 'autoclose' => true
            ]
        ]
    ]);
    
    ?>
  </div>  
    <div class='col-md-2'>
        <?= $form->field($model, 'worder_tel') ?>
    </div>


    <div class='col-md-2' style="margin-top: 22px;">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
