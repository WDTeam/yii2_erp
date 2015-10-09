<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\FinancePopOrderLog $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-pop-order-log-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'finance_pop_order_log_series_succeed_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 系统对账成功...']], 

'finance_pop_order_log_series_succeed_status_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 系统对账成功时间...']], 

'finance_pop_order_log_finance_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 财务确定 ...']], 

'finance_pop_order_log_finance_status_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 财务 1 失败...']], 

'finance_pop_order_log_finance_audit'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 财务未处理...']], 

'finance_pop_order_log_finance_audit_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 财务未处理时间...']], 

'create_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...']], 

'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 0 正常 1 删除...']], 

'finance_pay_order_num'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 官方系统订单号...', 'maxlength'=>40]], 

'finance_pop_order_number'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 第三方订单号...', 'maxlength'=>40]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
