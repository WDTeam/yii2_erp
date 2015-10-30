<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\OperationServiceCardInfo $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-service-card-info-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
'service_card_info_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 卡名...', 'maxlength'=>64]],	

'service_card_info_card_type'=>['type'=> Form::INPUT_DROPDOWN_LIST, 'items'=>$config['type'], 'options'=>['placeholder'=>'Enter 卡类型...']], 

'service_card_info_card_level'=>['type'=> Form::INPUT_DROPDOWN_LIST, 'items'=>$config['level'], 'options'=>['placeholder'=>'Enter 卡级别...']], 

'service_card_info_use_scope'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 使用范围...']], 

'service_card_info_valid_days'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 有效时间(天)...']], 

//'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...']], 

//'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 更改时间...']], 

'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 状态...']], 

'service_card_info_par_value'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 卡面金额...', 'maxlength'=>8]], 

'service_card_info_reb_value'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠金额...', 'maxlength'=>8]], 

 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
