<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
?>

<div class="vacationModal">

    <?php
        $form = ActiveForm::begin([
            'type' => ActiveForm::TYPE_HORIZONTAL,
            'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL],
        ]);
    ?>
    <?= $form->field($workerVacationModel, 'finance_pop_order_msg');?>
    
    <?=  Html::submitButton('确认',['class'=>'btn btn-primary btn-lg btn-block']);?>
    <?php ActiveForm::end();?>

</div>
