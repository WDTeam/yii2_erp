<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\date\DatePicker;
/**
 * @var yii\web\View $this
 * @var boss\models\FinanceRecordLogSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-record-log-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'finance_order_channel_name') ?>

    <? //= Html::selected(Yii::t('boss', 'Search'), ['class' => 'btn btn-primary']) ?>
    
    <?//= $form->field($model, 'finance_pay_channel_id') ?>
    
    <?= $form->field($model, 'finance_pay_channel_name')->widget(Select2::classname(), [
        'name' => 'finance_pay_channel_id',
        'hideSearch' => true,
        'data' => [1 => '美团', 2 => '大众点评',3=>'京东到家',4=>'淘宝热卖'],
        'options' => ['placeholder' => '选择订单渠道'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    
    ?>

    <?//= $form->field($model, 'finance_pay_channel_name') ?>

    <?php // echo $form->field($model, 'finance_record_log_succeed_count') ?>

    <?php // echo $form->field($model, 'finance_record_log_succeed_sum_money') ?>

    <?php // echo $form->field($model, 'finance_record_log_manual_count') ?>

    <?php // echo $form->field($model, 'finance_record_log_manual_sum_money') ?>

    <?php // echo $form->field($model, 'finance_record_log_failure_count') ?>

    <?php // echo $form->field($model, 'finance_record_log_failure_money') ?>

    <?php  echo $form->field($model, 'finance_record_log_confirm_name') ?>
    
    <?php echo  $form->field($model, 'create_time')->widget(DatePicker::classname(),[
        'name' => 'create_time',
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
        'value' => date('Y-m-d', $model->create_time),
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);
    
    ?>
    
    <?php//  echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('boss', '提交'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('boss', '重置'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>