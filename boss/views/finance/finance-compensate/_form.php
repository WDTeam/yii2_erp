<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\FinanceCompensate $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-compensate-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'finance_complaint_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 投诉Id...']], 

'worker_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨Id...']], 

'customer_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨Id...']], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 审核时间...']], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 申请时间...']], 

'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 0 正常 1删除...']], 

'finance_compensate_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter  赔偿金额...', 'maxlength'=>10]], 

'finance_compensate_reason'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter 赔偿原因...','rows'=> 6]], 

'comment'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter 备注，可能是未通过原因...','rows'=> 6]], 

'finance_compensate_oa_code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter OA批号...', 'maxlength'=>40]], 

'finance_compensate_coupon'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter  优惠券,可能是多个优惠券，用分号分隔...', 'maxlength'=>150]], 

'finance_compensate_proposer'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 申请人...', 'maxlength'=>20]], 

'finance_compensate_auditor'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 审核人...', 'maxlength'=>20]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
