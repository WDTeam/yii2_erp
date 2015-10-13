<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
?>


<?php
    $form = ActiveForm::begin([
//        'type' => ActiveForm::TYPE_HORIZONTAL,
        'action' => ['review?id='.$model->id.'&review_section='.$model->review_section.'&is_ok=0'],
        'method' => 'get',
    ]);
    echo $form->field($model, 'shop_name')->textInput(['disabled' => true]);

    echo $form->field($model, 'comment')->textarea(['name'=>'comment'])->label("审核不通过原因");

    echo Html::submitButton('确认',['class'=>'btn btn-primary btn-lg btn-block']);
    ActiveForm::end();
?>

