<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
?>


<?php
    $form = ActiveForm::begin([
//        'type' => ActiveForm::TYPE_HORIZONTAL,
        'action' => ['self-fulltime-worker-settle-done?id='.$model->id.'&settle_type='.$model->settle_type.'&review_section='.$model->review_section.'&is_ok=0'],
        'method' => 'get',
    ]);
    echo $form->field($model, 'worker_name')->textInput(['disabled' => true])->label('阿姨姓名');

    echo $form->field($model, 'comment')->textarea(['name'=>'comment'])->label("审核不通过原因");

    echo Html::submitButton('确认',['class'=>'btn btn-primary btn-lg btn-block']);
    ActiveForm::end();
?>

