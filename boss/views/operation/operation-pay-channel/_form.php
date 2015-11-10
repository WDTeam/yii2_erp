<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use boss\models\operation\OperationPayChannelSearch;
$model->operation_pay_channel_type=1;



?>

<div class="operation-pay-channel-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
    		'operation_pay_channel_type'=>['type'=> Form::INPUT_RADIO_LIST,
    		'items'=>OperationPayChannelSearch::configorder(),'options'=>[]],
'operation_pay_channel_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'支付渠道名称...', 'maxlength'=>50]], 
'operation_pay_channel_rate'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'比率...', 'maxlength'=>6]], 
    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
