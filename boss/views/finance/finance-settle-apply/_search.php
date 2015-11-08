<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use core\models\finance\FinanceSettleApplySearch;
?>

<div class="worker-search">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_VERTICAL,
        //'id' => 'login-form-inline',
        'action' => ['query'],
        'method' => 'get',
    ]); ?>
    
    <div class='col-md-3'>
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
    <?php echo  $form->field($model, 'settle_apply_create_end_time')->widget(DateControl::classname(),[
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
        <?= $form->field($model, 'worker_tel') ?>
    </div>
    <div class='col-md-2'>
        <?php 
            echo  $form->field($model, 'worker_type_id')->dropDownList([FinanceSettleApplySearch::SELF_OPERATION=>'自营',FinanceSettleApplySearch::NON_SELF_OPERATION=>'小家政']);
        ?>
    </div>
    <div class='col-md-2'>
        <?php 
            echo  $form->field($model, 'worker_identity_id')->dropDownList([FinanceSettleApplySearch::FULLTIME=>'全职',FinanceSettleApplySearch::PARTTIME=>'兼职',FinanceSettleApplySearch::PARKTIME=>'高峰',FinanceSettleApplySearch::INTERVALTIME=>'时段']);
        ?>
    </div>
    <input type="hidden" name="isExport" id = "isExport" value="0"/>
    <div class='col-md-2' style="margin-top: 22px;">
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
