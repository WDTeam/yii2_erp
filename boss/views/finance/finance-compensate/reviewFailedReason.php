<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
?>


<?php
    $form = ActiveForm::begin([
//        'type' => ActiveForm::TYPE_HORIZONTAL,
        'action' => ['review?id='.$model->id.'&is_ok=0'],
        'method' => 'get',
    ]);
    echo $form->field($model, 'worker_name')->textInput(['disabled' => true,'value'=>'陈阿姨'])->label('阿姨姓名');

    echo $form->field($model, 'comment')->textarea(['name'=>'comment'])->label("不通过原因");

    echo Html::submitButton('确认',['class'=>'btn btn-primary btn-lg btn-block']);
    ActiveForm::end();
?>

