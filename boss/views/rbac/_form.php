<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div class="rbac-form">
<?php $form = ActiveForm::begin(['layout'=>'horizontal']);?>
	  <?=$form->field($model, 'type')->dropDownList(['1'=>'角色','2'=>'权限']);  ?>
	  <?= $form->field($model, 'name')->textInput() ?>
	  <?= $form->field($model, 'description')->textInput() ?>
	  <?= $form->field($model, 'data')->textInput() ?>
	 
	 <div class="form-group">
	       <div class="col-sm-offset-3 col-sm-6">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
       </div>
<?php ActiveForm::end(); ?>
</div>