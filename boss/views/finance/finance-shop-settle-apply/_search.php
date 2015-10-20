<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use boss\models\FinanceShopSettleApplySearch;
use boss\widgets\ShopSelect;
use boss\models\FinanceSettleApplySearch;
?>

<div class="worker-search">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_VERTICAL,
        'action' => ['index?review_section='.$model->review_section],
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
    
    <!--<div class='col-md-2' >-->
    <?php 
//    echo  $form->field($model, 'finance_shop_settle_apply_starttime')->widget(DateControl::classname(),[
//        'type' => DateControl::FORMAT_DATE,
//         'value'=>$model->finance_shop_settle_apply_starttime,
//        'ajaxConversion'=>false,
//        'displayFormat' => 'php:Y-m-d',
//        'saveFormat'=>'php:U',
//        'options' => [
//            'pluginOptions' => [
//                 'autoclose' => true
//            ]
//        ]
//    ]);
    
//    ?>
  </div>  
    <!--<div class='col-md-2'>-->
    <?php 
//    echo  $form->field($model, 'finance_shop_settle_apply_endtime')->widget(DateControl::classname(),[
//        'type' => DateControl::FORMAT_DATE,
//        'value'=>$model->finance_shop_settle_apply_endtime,
//        'ajaxConversion'=>false,
//        'displayFormat' => 'php:Y-m-d',
//        'saveFormat'=>'php:U',
//        'options' => [
//            'pluginOptions' => [
//                 'autoclose' => true
//            ]
//        ]
//    ]);
    
    ?>
  <!--</div>--> 
     <?php 
    if($model->review_section == FinanceShopSettleApplySearch::BUSINESS_REVIEW){
        echo "<div class='col-md-2'>";
        echo  $form->field($model, 'finance_shop_settle_apply_status')->dropDownList([FinanceSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_INIT=>'待审核',FinanceSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_FINANCE_FAILED=>'财务审核未通过']);
        echo "</div> ";
    }
    ?>
  
  <?php 
    if($model->review_section == FinanceShopSettleApplySearch::FINANCE_REVIEW){
        echo "<div class='col-md-2'>";
        echo  $form->field($model, 'finance_shop_settle_apply_status')->dropDownList([FinanceSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_INIT=>'待审核',FinanceSettleApplySearch::FINANCE_SETTLE_APPLY_STATUS_FINANCE_PASSED=>'财务审核已通过,需要确认打款']);
        echo "</div> ";
    }
    ?>

    <div class='col-md-2' style="margin-top: 22px;">
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
