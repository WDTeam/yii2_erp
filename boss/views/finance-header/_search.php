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

    <?= $form->field($model, 'finance_header_name') ?>

    <?//= $form->field($model, 'finance_order_channel_id') ?>

    <?//= $form->field($model, 'finance_order_channel_name') ?>
    
    <?= $form->field($model, 'finance_order_channel_name')->widget(Select2::classname(), [
        'name' => 'finance_order_channel_name',
        'hideSearch' => true,
        'data' => [1 => '美团', 2 => '大众点评',3=>'京东到家',4=>'淘宝热卖'],
        'options' => ['placeholder' => '选择订单渠道'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    
    ?>
    
    

    <?//= $form->field($model, 'finance_pay_channel_id') ?>

    <?php  //echo $form->field($model, 'finance_pay_channel_name') ?>
    
    <?= $form->field($model, 'finance_pay_channel_name')->widget(Select2::classname(), [
        'name' => 'finance_pay_channel_name',
        'hideSearch' => true,
        'data' => [1 => '微信', 2 => '支付宝',3=>'财付通',4=>'银联'],
        'options' => ['placeholder' => '选择支付渠道'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    
    ?>
    

    <?php // echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('boss', '确定'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('boss', '重置'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
