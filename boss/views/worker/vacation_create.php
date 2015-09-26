<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;
use kartik\widgets\Select2; // or kartik\select2\Select2
?>

<div class="">

<?php /*$form = ActiveForm::begin([
    'type'=>ActiveForm::TYPE_HORIZONTAL,
    'action'=>['vacation-create', 'worker_id'=>$workerModel->id]
]); */ ?>
    <!--<div class="form-group">
        <div class="col-sm-offset-0 col-sm-12">
            <?php // Html::submitButton('确认',['class'=>'btn btn-primary btn-lg btn-block']);?>
        </div>
    </div>-->
    <?php /* ActiveForm::end(); */?>
<?php
    $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL],
    ]);
    echo $form->field($workerModel, 'worker_name');

    echo $form->field($workerVacationModel, 'worker_vacation_extend');

    echo $form->field($workerVacationModel, 'worker_vacation_start_time')->widget(DatePicker::classname(), [
    'name' => 'worker_vacation_start_time',
    'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    'pluginOptions' => [
        'autoclose' => true,
        'format' => 'yyyy-mm-dd'
    ]
    ]);
    echo $form->field($workerVacationModel, 'worker_vacation_finish_time')->widget(DatePicker::classname(), [
        'name' => 'worker_vacation_finish_time',
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,

        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);
    echo Html::submitButton('确认',['class'=>'btn btn-primary btn-lg btn-block']);
    ActiveForm::end();
?>

</div>
