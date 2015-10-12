<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;
use boss\models\FinanceSettleApplySearch;
use boss\widgets\ShopSelect;
use boss\models\FinanceShopSettleApplySearch;
?>

<div class="worker-search">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_VERTICAL,
        //'id' => 'login-form-inline',
        'action' => ['self-fulltime-worker-settle-index?settle_type='.$model->settle_type.'&review_section='.$model->review_section],
        'method' => 'get',
    ]); ?>
    
        <?php
            if($model->settle_type == FinanceSettleApplySearch::SELF_FULLTIME_WORKER_SETTELE){
                echo "<div class='col-md-2'>";
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
        echo "<div class='col-md-2'>";
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
        echo "<div class='col-md-2'>";
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
    if(($model->settle_type == FinanceSettleApplySearch::SHOP_WORKER_SETTELE) || ($model->settle_type ==FinanceSettleApplySearch::ALL_WORKER_SETTELE)){
        echo "<div class='col-md-4' style='margin-top: 22px;'>";
        echo  ShopSelect::widget([
            'model'=>$model,
            'shop_manager_id'=>'shop_manager_id',
            'shop_id'=>'shop_id',
            ]);
        echo "</div> ";
    }
    ?>
  
    <div class='col-md-2'>
        <?= $form->field($model, 'worder_tel'); ?>
    </div>
    
     <?php 
    if($model->settle_type !=FinanceSettleApplySearch::ALL_WORKER_SETTELE){
        echo "<div class='col-md-2'>";
        echo  $form->field($model, 'finance_settle_apply_status')->dropDownList([FinanceSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_INIT=>'待审核',FinanceSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_FINANCE_FAILED=>'财务审核未通过']);
        echo "</div> ";
    }
    ?>


    <div class='col-md-3' style="margin-top: 22px;">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        <?php 
        if(($model->review_section == FinanceShopSettleApplySearch::BUSINESS_REVIEW)){
            echo  Html::a(Yii::t('app', '人工结算'), ['worker-manual-settlement-index?settle_type='.$model->settle_type.'&review_section='.$model->review_section],['class' => 'btn btn-default']);
        }
        ?>
    </div>

    
    

</div>
