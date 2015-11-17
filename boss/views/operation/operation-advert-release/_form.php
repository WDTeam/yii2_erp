<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\datecontrol\Module;
use dosamigos\datetimepicker\DateTimePicker;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\OperationAdvertRelease $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div class="operation-advert-release-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'status'=>[
                'type'=> Form::INPUT_RADIO_LIST,
                'items'=>[0 => '未上线', 1 => '上线', 2 => '下线'],
                'label'=>'在线状态',
                'options'=>['placeholder'=>'请输入在线状态：1上线，2下线...']
            ],
        ]
    ]);
    ?>

    <div class="col-md-5">
    <?= $form->field($model, 'starttime')->widget(
        DateTimePicker::className(), [
            'language' => 'zh-CN',
            'size' => 'ms',
            'template' => '<div class="well well-sm" style="background-color: #fff; width:250px;font-size:14px;">{input}</div>',
            'pickButtonIcon' => 'glyphicon glyphicon-time',
            'inline' => true,
            'clientOptions' => [
               'todayHighlight' => 1,
               'forceParse' => 0,
                //'startView' => 3,
                'weekStart' => 1,
                'minView' => 0,
                'maxView' => 1,
                'autoclose' => 1,
                'linkFormat' => 'yyyy-mm-dd hh:ii:ss', // if inline = true
                'todayBtn' => true,
                'startDate' => date('Y-m-d'),
            ]
    ]);?>
    </div>

    <div class="col-md-5">
    <?= $form->field($model, 'endtime')->widget(
        DateTimePicker::className(), [
            'language' => 'zh-CN',
            'size' => 'ms',
            'template' => '<div class="well well-sm" style="background-color: #fff; width:250px;font-size:14px;">{input}</div>',
            'pickButtonIcon' => 'glyphicon glyphicon-time',
            'inline' => true,
            'clientOptions' => [
               'todayHighlight' => 1,
               'forceParse' => 0,
                //'startView' => 3,
                'weekStart' => 1,
                'minView' => 0,
                'maxView' => 1,
                'autoclose' => 1,
                'linkFormat' => 'yyyy-mm-dd hh:ii:ss', // if inline = true
                'todayBtn' => true,
                'startDate' => date('Y-m-d'),
            ]
    ]);?>
    </div>


    <?php
        echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
     ActiveForm::end(); 
    ?>

</div>
