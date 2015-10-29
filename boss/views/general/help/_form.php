<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var dbbase\models\Help $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="help-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'help_question'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 问题...', 'maxlength'=>64]], 

'help_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 0隐藏，1显示...']], 

'help_sort'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 越小排在前面...']], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Created At...']], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Update At...']], 

'help_solution'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter 回答...','rows'=> 6]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
