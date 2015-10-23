<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\finance\FinanceWorkerOrderIncome $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-worker-order-income-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'worder_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨id...']], 

'finance_worker_order_income_type'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨收入类型，1订单收入（线上支付），2订单收入（现金），3路补，4晚补，5扑空补助,6渠道奖励...']], 

'order_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单id...']], 

'finance_worker_order_complete_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单完成时间...']], 

'order_booked_count'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 预约服务数量，即工时...']], 

'isSettled'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 是否已结算，0为未结算，1为已结算...']], 

'finance_settle_apply_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 结算申请Id...']], 

'isdel'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 是否被删除，0为启用，1为删除...']], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 结算时间...']], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...']], 

'finance_worker_order_income_starttime'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 本次结算开始时间...']], 

'finance_worker_order_income_endtime'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 本次结算结束时间...']], 

'finance_worker_order_income'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨收入...', 'maxlength'=>10]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
