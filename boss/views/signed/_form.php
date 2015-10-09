<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model backend\models\Signed */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="signed-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($user, 'id', ['labelOptions' => ['class' => 'hidden']])->textInput(['type' => 'hidden', 'name' => 'Signed[uid]']) ?>

    <?= $form->field($user, 'username')->textInput(['maxlength' => true, 'name' => 'Signed[uname]']) ?>
    
    <?= $form->field($user, 'mobile')->textInput(['maxlength' => true, 'name' => 'Signed[mobile]']) ?>

    <?= $form->field($user, 'idnumber')->textInput(['maxlength' => true, 'name' => 'Signed[identity_number]']) ?>

    <?= $form->field($user, 'address')->textInput(['maxlength' => true, 'name' => 'Signed[address]']) ?>

    <?= $form->field($user, 'ecp')->textInput(['maxlength' => true, 'name' => 'Signed[emergency_person]']) ?>

    <?= $form->field($user, 'ecn')->textInput(['maxlength' => true, 'name' => 'Signed[emergency_contact]']) ?>

    <?= $form->field($model, 'shopname')->dropDownList(['通州店','朝阳店'], ['id'=>'shopid'])?>

    <?= $form->field($model, 'bankcard')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deposit')->textInput() ?>

    <?= $form->field($model, 'contract_number')->textInput(['maxlength' => true, 'readonly' => true]) ?>
    
    <?php
        echo '<label class="control-label">'.$model->attributeLabels()['contract_time'].'</label>';
        echo DatePicker::widget([
        'name' => 'Signed[contract_time]',
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
        'value' => $model->contract_time,
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);
    ?>

    <?= $form->field($model, 'signed')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sendSome')->checkboxList(['0'=>'工服','1'=>'工牌','2'=>'工具'],['class'=>'label-group']); ?>
    
    <?= $form->field($model, 'picture')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
