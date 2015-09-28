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
<div class='col-md-2'>
    <?= $form->field($model, 'finance_order_channel_name') ?>
</div>

    <? //= Html::selected(Yii::t('boss', 'Search'), ['class' => 'btn btn-primary']) ?>
    
    <?//= $form->field($model, 'finance_pay_channel_id') ?>
<div class='col-md-2'>
    <?= $form->field($model, 'finance_pay_channel_id')->widget(Select2::classname(), [
        'name' => 'finance_pay_channel_id',
        'hideSearch' => true,
        'data' =>$odrinfo,
        'options' => ['placeholder' => '选择订单渠道','class' => 'col-md-2'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    
    ?>
</div>
    <?//= $form->field($model, 'finance_pay_channel_name') ?>

    <?php // echo $form->field($model, 'finance_record_log_succeed_count') ?>

    <?php // echo $form->field($model, 'finance_record_log_succeed_sum_money') ?>

    <?php // echo $form->field($model, 'finance_record_log_manual_count') ?>

    <?php // echo $form->field($model, 'finance_record_log_manual_sum_money') ?>

    <?php // echo $form->field($model, 'finance_record_log_failure_count') ?>

    <?php // echo $form->field($model, 'finance_record_log_failure_money') ?>
<div class='col-md-2'>
    <?php  echo $form->field($model, 'finance_record_log_confirm_name') ?>
</div> 
<div class='col-md-2'>
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
  </div>   
    <?php //  echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <div class="form-group">
    <div class='col-md-2' style="    margin-top: 22px;">
        <?= Html::submitButton(Yii::t('boss', '提交'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('boss', '重置'), ['class' => 'btn btn-default']) ?>
    </div>   
    </div>

    <?php ActiveForm::end(); ?>

</div>
