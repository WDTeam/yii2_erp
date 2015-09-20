<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\WorkerBlock $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="worker-block-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'worker_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 主表阿姨id...']], 

'worker_block_type'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨封号类型 0短期1永久...']], 

'worker_block_start'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 封号开始时间...']], 

'worker_block_finish'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 封号结束时间...']], 

'created_ad'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...']], 

'updated_ad'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 最后更新时间...']], 

'admin_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 管理员id...']], 

'worker_block_reason'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨封号原因...', 'maxlength'=>16]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
