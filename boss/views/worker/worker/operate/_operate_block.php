<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;
use boss\models\worker\WorkerBlock;
$workerBlockModel = WorkerBlock::find()->where(['worker_id'=>$worker_id])->one();
if($workerBlockModel===null){
    $workerBlockModel = new WorkerBlock();
    $workerBlockModel->worker_block_status = 0;
}
?>

<div class="">

<?php
    $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL],
        'action'=>['operate-block', 'workerId'=>$worker_id]
    ]);
    echo '<div class="form-group field-worker-worker_name required">';
    echo '<label class="control-label col-sm-3" for="worker-worker_name">阿姨姓名</label>';
    echo '<div class="col-sm-9"><input id="worker-worker_name" class="form-control" type="text" disabled="" value="'.$worker_name.'" ></div>';
    echo '<div class="col-sm-offset-3 col-sm-9"></div>';
    echo '<div class="col-sm-offset-3 col-sm-9"><div class="help-block"></div></div>';
    echo '</div>';

    echo $form->field($workerBlockModel, 'worker_block_reason');
    echo $form->field($workerBlockModel, 'id')->hiddenInput();
    echo $form->field($workerBlockModel, 'daterange')->widget(DateRangePicker::classname(), [
        'name'=>'daterange',
        'useWithAddon'=>true,
        'language'=>'zh-CN',
        'hideInput'=>true,
        'presetDropdown'=>false,
        'pluginOptions'=>[
            'locale'=>['format'=>'date'],
            'separator'=>' 至 ',
            'opens'=>'right',
        ]
    ]);
    echo Html::submitButton('确认',['class'=>'btn btn-primary btn-lg btn-block']);
    ActiveForm::end();
?>

</div>
<?php
$this->registerJs(<<<JSCONTENT
    $('.field-workerblock-id').hide();
JSCONTENT
);

?>
<script>
</script>