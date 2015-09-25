<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var boss\models\FinanceHeaderSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-header-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?//= $form->field($model, 'id') ?>
<div class='col-md-2'>
    <?= $form->field($model, 'finance_header_title') ?>
     </div>
    <div class='col-md-2'>
    <?= $form->field($model, 'finance_order_channel_id')->widget(Select2::classname(), [
        'name' => 'finance_order_channel_id',
        'hideSearch' => true,
        'data' => $odrinfo,
        'options' => ['placeholder' => '选择订单渠道','class' => 'col-md-2'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
   
    ?>
     </div>
    <div class=col-md-3>
    <?= $form->field($model, 'finance_pay_channel_id')->widget(Select2::classname(), [
        'name' => 'finance_pay_channel_id',
        'hideSearch' => true,
        'data' => $ordedat,
        'options' => ['placeholder' => '选择支付渠道','class' => 'col-md-2'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    
    ?>
    </div>
    <div class="form-group">
     <div class='col-md-2' style="    margin-top: 22px;">
        <?= Html::submitButton(Yii::t('boss', '确定'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('boss', '重置'), ['class' => 'btn btn-default']) ?>
        </div>  
    </div>

    <?php ActiveForm::end(); ?>

</div>
