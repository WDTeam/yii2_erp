<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\FinanceOrderChannel $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-order-channel-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'finance_order_channel_sort'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 排序...']], 

'finance_order_channel_is_lock'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 1 上架 2 下架...']], 

'create_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 增加时间...']], 

'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 0 正常 1 删除...']], 

'finance_order_channel_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 渠道名称...', 'maxlength'=>50]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
