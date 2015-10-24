<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use common\models\finance\FinancePayChannel;
$model->finance_order_channel_is_lock=1;
$model->is_del=0;
/**
 * @var yii\web\View $this
 * @var common\models\FinanceOrderChannel $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-order-channel-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
     echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
     		'pay_channel_id'=>[
     		'type' => Form::INPUT_DROPDOWN_LIST,
     		'items' => FinancePayChannel::get_pay_channel_list(),
     		'options' => [
     		'prompt' => '请选择支付渠道',
     		],
     		],
     		//'pay_channel_id'=>['type'=> Form::INPUT_DROPDOWN_LIST, 'options'=>['placeholder'=>'比例...']],
     		
     		'finance_order_channel_rate'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'比例...']], 
     		
'finance_order_channel_sort'=>['type'=> Form::INPUT_RADIO_LIST, 'items'=>['1' => '确定', '2' => '取消'],'options'=>[
    		
]], 

    		'finance_order_channel_is_lock'=>['type'=> Form::INPUT_RADIO_LIST, 'items'=>['1' => '确定', '2' => '取消'],
    		'options'=>[
    		
]],

     		'is_del'=>['type'=> Form::INPUT_RADIO_LIST, 'items'=>['1' => '开启', '2' => '关闭'],
     		'options'=>[
     		
     		]],
     		
//'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 0 正常 1 删除...']], 
  		
'finance_order_channel_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'渠道名称...', 'maxlength'=>50]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
