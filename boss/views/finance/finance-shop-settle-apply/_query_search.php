<?php

use kartik\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

use boss\widgets\ShopSelect;

use core\models\finance\FinanceShopSettleApplySearch;

use yii\helpers\Html;


?>

<div class="worker-search">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_VERTICAL,
        'action' => ['query'],
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
    
<div class='col-md-3' >
    <?php 
    echo  $form->field($model, 'settle_apply_create_start_time')->widget(DateControl::classname(),[
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
    <?php 
    echo  $form->field($model, 'settle_apply_create_end_time')->widget(DateControl::classname(),[
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
