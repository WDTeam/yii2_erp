<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\User */

$this->title = '阿姨详细信息';
//$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

     <div class="user-form">
    <?php $form = ActiveForm::begin(); ?>
     
     <?= $form->field($model, 'whatodo')->checkboxList([1=>'家庭保洁',2=>'企业保洁']) ?>

    <?= $form->field($model, 'from_type')->radioList([1=>'e家洁',2=>'小家政公司',3=>'中介（劳务公司）',4=>'推广平台1',5=>'推广平台2']) ?>
         
    <?= $form->field($model, 'when')->radioList([1=>'随时可以',2=>'周六周日',3=>'不确定']) ?>
    <?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>
  
    <?= $form->field($model, 'idnumber')->textInput(['maxlength' => 24]) ?>
     
    <?= $form->field($model, 'ecn')->textInput(['maxlength' => 15]) ?>   

   
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : '添加个人详细信息', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

     





</div>
