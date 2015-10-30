<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;
use boss\models\worker\WorkerVacation;

$workerVacationModel = new WorkerVacation;
$workerVacationModel->worker_vacation_type = 1;

?>

<div class="">

    <?php
        $form = ActiveForm::begin([
            'type' => ActiveForm::TYPE_HORIZONTAL,
            'action'=>'operate-vacation?workerId='.$worker_id,
            'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL,],
        ]);
    ?>
    <?= $form->field($workerVacationModel,'id')->hiddenInput();?>
    <div class="form-group field-worker-worker_name required">
        <label class="control-label col-sm-3" for="worker-worker_name">阿姨姓名</label>
        <div class="col-sm-9"><input id="worker-worker_name" class="form-control" name="Worker[worker_name]" value='<?php echo $worker_name?>' disabled="" ></div>
        <div class="col-sm-offset-3 col-sm-9"></div>
        <div class="col-sm-offset-3 col-sm-9"><div class="help-block"></div></div>
    </div>

    <div class="form-group field-worker-worker_name required">
        <label class="control-label col-sm-3" for="worker-worker_name">阿姨请假类型</label>
        <div class="col-sm-9"><input id="worker-worker_name" class="form-control" name="Worker[worker_name]" value='休假' disabled="" ></div>
        <div class="col-sm-offset-3 col-sm-9"></div>
        <div class="col-sm-offset-3 col-sm-9"><div class="help-block"></div></div>
    </div>
    <?= $form->field($workerVacationModel,'worker_vacation_type')->hiddenInput();?>
    <?=  $form->field($workerVacationModel, 'daterange')->widget(DateRangePicker::classname(), [
        'name'=>'daterange',
        'useWithAddon'=>true,
        'language'=>'zh-CN',
        'hideInput'=>true,
        'presetDropdown'=>false,
        'pluginOptions'=>[
            'locale'=>['format'=>'date'],
            'separator'=>' 至 ',
            'opens'=>'right'
        ]
    ]);?>


    <?= $form->field($workerVacationModel, 'worker_vacation_extend');?>

    <?=  Html::submitButton('确认',['class'=>'btn btn-primary btn-lg btn-block']);?>
    <?php ActiveForm::end();?>

</div>
<?php
$this->registerJs(<<<JSCONTENT
    $('.field-workervacation-worker_vacation_type').hide();
    $('.field-workervacation-id').hide();
JSCONTENT
);

?>