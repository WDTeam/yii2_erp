<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var dbbase\models\finance\FinanceSettleApply $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-settle-apply-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'worker_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨id...']], 

'worker_tel'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨电话...', 'maxlength'=>10]], 

'worker_type_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨类型Id...']], 

'worker_type_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨职位类型...', 'maxlength'=>1]], 

'finance_settle_apply_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 申请结算金额...', 'maxlength'=>10]], 

'finance_settle_apply_man_hour'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单总工时...']], 

'finance_settle_apply_order_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 工时费...', 'maxlength'=>10]], 

'finance_settle_apply_order_cash_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 收取的现金...', 'maxlength'=>10]], 

'finance_settle_apply_non_order_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 非订单收入，即帮补费...', 'maxlength'=>10]], 

'finance_settle_apply_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 申请结算状态，-3财务打款失败；-2财务审核不通过；-1线下审核不通过；0提出申请，正在线下审核；1线下审核通过，等待财务审核；2财务审核通过，等待财务打款；3财务打款成功，申请完结；...']], 

'finance_settle_apply_cycle'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 结算周期，1周结，2月结...']], 

'isdel'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 是否被删除，0为启用，1为删除...']], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 审核时间...']], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 申请时间...']], 

'finance_settle_apply_reviewer'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 审核人姓名...', 'maxlength'=>20]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
