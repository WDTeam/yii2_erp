<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;
use kartik\widgets\Select2; // or kartik\select2\Select2
?>

<div class="">

    <?php
        $form = ActiveForm::begin([
            'type' => ActiveForm::TYPE_HORIZONTAL,
            'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL],
        ]);
    ?>
    <div class="form-group field-worker-worker_name required">
        <label class="control-label col-sm-3" for="worker-worker_name">阿姨姓名</label>
        <div class="col-sm-9"><textarea id="worker-worker_name" class="form-control" name="Worker[worker_name]" disabled="" ><?php echo $workerName?></textarea></div>
        <div class="col-sm-offset-3 col-sm-9"></div>
        <div class="col-sm-offset-3 col-sm-9"><div class="help-block"></div></div>
    </div>

    <?= $form->field($workerVacationModel, 'worker_vacation_extend');?>
    <?= $form->field($workerVacationModel, 'worker_vacation_type')->widget(Select2::classname(), [
        'name' => 'worker_vacation_type',
        'hideSearch' => true,
        'data' => [1 => '休假', 2 => '事假'],
        'options' => ['placeholder' => '选择阿姨请假类型'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
     ?>
    <?=  $form->field($workerVacationModel, 'worker_vacation_start_time')->widget(DatePicker::classname(), [
    'name' => 'worker_vacation_start_time',
    'type' => DatePicker::TYPE_COMPONENT_PREPEND,
    'pluginOptions' => [
        'autoclose' => true,
        'format' => 'yyyy-mm-dd'
    ]
    ]);?>
    <?=  $form->field($workerVacationModel, 'worker_vacation_finish_time')->widget(DatePicker::classname(), [
        'name' => 'worker_vacation_finish_time',
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,

        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);?>
    <?=  Html::submitButton('确认',['class'=>'btn btn-primary btn-lg btn-block']);?>
    <?php ActiveForm::end();?>

</div>
