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
        'action' => ['self-fulltime-worker-settle-index'],
        'method' => 'get',
    ]); ?>
    
    <div class='col-md-2'>
        <?= $form->field($model, 'settleMonth')->widget(DatePicker::classname(), [
                    'name' => 'settleMonth',
                    'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm',
                        'startView'=>1,
                        'minViewMode'=>1,
                    ]
                ]); ?>
    </div>
    <div class='col-md-2'>
        <?= $form->field($model, 'worder_tel') ?>
    </div>


    <div class='col-md-2' style="margin-top: 22px;">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        
    </div>

    <?php ActiveForm::end(); ?>
    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_VERTICAL,
        //'id' => 'login-form-inline',
        'action' => ['worker-manual-settlement-index'],
        'method' => 'get',
    ]); ?>
    <div class='col-md-1' style="margin-top: 22px;">
        <?= Html::submitButton(Yii::t('app', '人工结算'), ['class' => 'btn btn-default']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
