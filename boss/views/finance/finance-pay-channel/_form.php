<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

$model->finance_pay_channel_is_lock=1;
$model->is_del=0;
/**
 * @var yii\web\View $this
 * @var dbbase\models\FinancePayChannel $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-pay-channel-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'finance_pay_channel_rank'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'排序...']],   		
'finance_pay_channel_is_lock'=>['type'=> Form::INPUT_RADIO_LIST, 'items'=>['1' => '开启', '2' => '关闭'],
    		'options'=>[]],    				
//'create_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 增加时间...']], 

//'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 0 正常 1 删除...']], 

'finance_pay_channel_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'渠道名称...', 'maxlength'=>50]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
