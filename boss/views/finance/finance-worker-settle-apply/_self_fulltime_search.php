<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use core\models\finance\FinanceWorkerSettleApplySearch;
use core\models\finance\FinanceShopSettleApplySearch;
use kartik\datecontrol\DateControl;
?>

<div class="worker-search">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_VERTICAL,
        'method' => 'get',
    ]); ?>
    
    <div class='col-md-2'>
        <?= $form->field($model, 'worker_tel'); ?>
    </div>
    
     <?php 
    if($model->settle_type !=FinanceWorkerSettleApplySearch::ALL_WORKER_SETTELE){
        echo "<div class='col-md-2'>";
        echo  $form->field($model, 'finance_worker_settle_apply_status')->dropDownList([FinanceWorkerSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_INIT=>'待审核',FinanceWorkerSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_FINANCE_FAILED=>'财务审核未通过']);
        echo "</div> ";
    }
    ?>
    
    <?php 
        if($model->settle_type ==FinanceWorkerSettleApplySearch::ALL_WORKER_SETTELE){
            echo "<div class='col-md-2'>";
            echo  $form->field($model, 'finance_worker_settle_apply_status')->dropDownList([FinanceWorkerSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_INIT=>'待审核',FinanceWorkerSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_FINANCE_PASSED=>'财务审核已通过,需要确认打款']);
            echo "</div> ";
        }
        ?>

    <div class='col-md-5 form-inline' style="margin-top: 22px;">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        <?php 
        if(($model->review_section == FinanceShopSettleApplySearch::BUSINESS_REVIEW)){
            echo  Html::a(Yii::t('app', '人工结算'), ['worker-manual-settlement-index?settle_type='.$model->settle_type.'&review_section='.$model->review_section],['class' => 'btn btn-default']);
        }
        ?>
    </div>

    
     <?php ActiveForm::end(); ?>

</div>
