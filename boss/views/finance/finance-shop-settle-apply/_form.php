<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\finance\FinanceShopSettleApply $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-shop-settle-apply-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'shop_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 门店id...']], 

'shop_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 门店名称...', 'maxlength'=>100]], 

'shop_manager_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 归属家政id...']], 

'shop_manager_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 归属家政名称...', 'maxlength'=>100]], 

'finance_shop_settle_apply_cycle'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 结算周期，1周结，2月结...']], 

'finance_shop_settle_apply_cycle_des'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter 结算周期，周结，月结...','rows'=> 6]], 

'finance_shop_settle_apply_order_count'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 完成总单量...']], 

'finance_shop_settle_apply_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 申请结算状态，-4财务确认结算未通过;-3财务审核不通过；-2线下审核不通过；-1门店财务审核不通过；0提出申请，正在门店财务审核；1门店财务审核通过，等待线下审核；2线下审核通过，等待财务审核；3财务审核通过，等待财务确认结算；4财务确认结算；...']], 

'finance_shop_settle_apply_starttime'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 本次结算开始时间(统计)，例如：2015.9.1 00:00:00对应的int值...']], 

'finance_shop_settle_apply_endtime'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 本次结算结束时间(统计)，例如：2015.9.30 23:59:59对应的int值...']], 

'isdel'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 是否被删除，0为启用，1为删除...']], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 审核时间...']], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 申请时间...']], 

'finance_shop_settle_apply_fee_per_order'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 每单管理费...', 'maxlength'=>10]], 

'finance_shop_settle_apply_fee'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 管理费...', 'maxlength'=>10]], 

'finance_shop_settle_apply_reviewer'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 审核人姓名...', 'maxlength'=>20]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
