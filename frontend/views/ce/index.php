<?php 

use yii\helpers\html;
use yii\widgets\ActiveForm;

?>



<?php  $form=ActiveForm::begin(); ?>


 <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

 <?= $form->field($model, 'idnumber')->textInput(['maxlength' => true]) ?>


<?php ActiveForm::end(); ?>