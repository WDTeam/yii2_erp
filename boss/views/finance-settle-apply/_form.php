<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\FinanceSettleApply $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-settle-apply-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'worder_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Worder ID...']], 

'settle_apply_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Settle Apply Money...', 'maxlength'=>10]], 

'settle_apply_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Settle Apply Status...']], 

'isdel'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Isdel...']], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 更新时间...']], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...']], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
