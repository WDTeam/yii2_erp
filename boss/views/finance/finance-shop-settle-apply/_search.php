<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use core\models\finance\FinanceShopSettleApplySearch;
use boss\widgets\ShopSelect;
use core\models\finance\FinanceWorkerSettleApplySearch;
?>

<div class="worker-search">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_VERTICAL,
        'method' => 'get',
    ]); ?>
    <div class='col-md-4' style='margin-top: 22px;'>
    <?php 
    echo ShopSelect::widget([
            'model'=>$model,
            'shop_manager_id'=>'shop_manager_id',
            'shop_id'=>'shop_id',
            ]);
    ?>
    </div>
    
     <?php 
    if($model->review_section == FinanceShopSettleApplySearch::BUSINESS_REVIEW){
        echo "<div class='col-md-2'>";
        echo  $form->field($model, 'finance_shop_settle_apply_status')->dropDownList([FinanceWorkerSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_INIT=>'待审核',FinanceWorkerSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_FINANCE_FAILED=>'财务审核未通过']);
        echo "</div> ";
    }
    ?>
  
  <?php 
    if($model->review_section == FinanceShopSettleApplySearch::FINANCE_REVIEW){
        echo "<div class='col-md-2'>";
        echo  $form->field($model, 'finance_shop_settle_apply_status')->dropDownList([FinanceWorkerSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_INIT=>'待审核',FinanceWorkerSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_FINANCE_PASSED=>'财务审核已通过,需要确认打款']);
        echo "</div> ";
    }
    ?>

    <div class='col-md-4 form-inline' style="margin-top: 22px;">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        <?php 
        if(($model->review_section == FinanceShopSettleApplySearch::BUSINESS_REVIEW)){
            echo  Html::a(Yii::t('app', '人工结算'), ['shop-manual-settlement-index?review_section='.$model->review_section],['class' => 'btn btn-default']);
        }
        ?>
    </div>

    <?php ActiveForm::end(); ?>
    
</div>
