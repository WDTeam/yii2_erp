<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\FinanceRecordLog $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-record-log-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'finance_order_channel_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 对账名称id...']], 

'finance_pay_channel_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 收款渠道id...']], 

'finance_record_log_succeed_count'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 成功记录数...']], 

'finance_record_log_manual_count'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 人工确认笔数...']], 

'finance_record_log_failure_count'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 失败笔数...']], 

'create_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...']], 

'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 0 正常 1 删除...']], 

'finance_record_log_succeed_sum_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 成功记录数总金额...', 'maxlength'=>8]], 

'finance_record_log_manual_sum_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 人工确认金额...', 'maxlength'=>8]], 

'finance_record_log_failure_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 失败总金额...', 'maxlength'=>8]], 

'finance_record_log_fee'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务费...', 'maxlength'=>8]], 

'finance_order_channel_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 对账名称...', 'maxlength'=>100]], 

'finance_pay_channel_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 收款渠道名称...', 'maxlength'=>100]], 

'finance_record_log_confirm_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 对账人...', 'maxlength'=>30]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
