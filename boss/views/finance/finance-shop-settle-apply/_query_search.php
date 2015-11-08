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
  <input type="hidden" name="isExport" id = "isExport" value="0"/>

    <div class='col-md-4 form-inline' style="margin-top: 22px;">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
       <?= Html::submitButton(Yii::t('app', '导出报表'), ['class' => 'btn btn-default','id'=>'export',]) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php 
         
            $js=<<<JS
                    $("#export").click(
                        function(){
                            $('#isExport').val(1);
                            return true;
                        }
                    );
JS;
        $this->registerJs(
                $js
        );
         ?>
</div>
