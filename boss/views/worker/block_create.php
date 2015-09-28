<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;
use kartik\widgets\Select2; // or kartik\select2\Select2
use kartik\field\FieldRange;
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
        //'action'=>['block-create', 'id'=>$workerBlockmodel->id]
    ]);
    echo $form->field($workerModel, 'worker_name')->textInput(['disabled' => true]);

    echo $form->field($workerBlockmodel, 'worker_block_reason');

    echo $form->field($workerBlockmodel, 'worker_block_start_time')->widget(DatePicker::classname(), [
    'name' => 'worker_block_start_time',
    'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    'pluginOptions' => [
        'autoclose' => true,
        'format' => 'yyyy-mm-dd',
        'saveFormat' => 'php:U'
    ]
    ]);
    echo $form->field($workerBlockmodel, 'worker_block_finish_time')->widget(DatePicker::classname(), [
        'name' => 'worker_block_finish_time',
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,

        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
        ]
    ]);


    echo Html::submitButton('确认',['class'=>'btn btn-primary btn-lg btn-block']);
    ActiveForm::end();
?>

</div>
