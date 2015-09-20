<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\OperationCity $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-city-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'city_is_online'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 城市是否上线（1为上线，2为下线）...']], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...']], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 编辑时间...']], 

'city_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 城市名称...', 'maxlength'=>30]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
