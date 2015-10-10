<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;
use boss\models\FinanceSettleApplySearch;
use boss\widgets\ShopSelect;
?>

<div class="worker-search">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_VERTICAL,
        //'id' => 'login-form-inline',
        'action' => ['self-fulltime-worker-settle-index'],
        'method' => 'get',
    ]); ?>
    
        <?php
            if($model->settle_type == FinanceSettleApplySearch::SELF_FULLTIME_WORKER_SETTELE){
                echo "<div class='col-md-3'>";
                echo $form->field($model, 'settleMonth')->widget(DatePicker::classname(), [
                            'name' => 'settleMonth',
                            'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm',
                                'startView'=>1,
                                'minViewMode'=>1,
                            ]
                        ]); 
                echo "</div> ";
            }
        ?>
    <?php 
    if($model->settle_type == FinanceSettleApplySearch::SELF_PARTTIME_WORKER_SETTELE){
        echo "<div class='col-md-3'>";
        echo  $form->field($model, 'finance_settle_apply_starttime')->widget(DateControl::classname(),[
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
        echo "</div> ";
    }
    ?>
    
    <?php 
    if($model->settle_type == FinanceSettleApplySearch::SELF_PARTTIME_WORKER_SETTELE){
        echo "<div class='col-md-3'>";
        echo  $form->field($model, 'finance_settle_apply_endtime')->widget(DateControl::classname(),[
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
        echo "</div> ";
    }
    ?>
    
     <?php 
    if($model->settle_type == FinanceSettleApplySearch::SHOP_WORKER_SETTELE){
        echo "<div class='col-md-4' style='margin-top: 26px;'>";
        echo  ShopSelect::widget([
            'model'=>$model,
            'shop_manager_id'=>'shop_manager_id',
            'shop_id'=>'shop_id',
            ]);
        echo "</div> ";
    }
    ?>
  
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
