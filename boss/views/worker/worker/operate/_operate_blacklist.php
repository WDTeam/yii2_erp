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

<?php
    $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL],
        'action'=>['operate-blacklist', 'id'=>$workerModel->id]
    ]);
    //echo $form->field($workerBlockmodel, 'worker_name')->textInput(['disabled' => true]);
    echo '<div class="form-group field-worker-worker_name required">';
    echo '<label class="control-label col-sm-3" for="worker-worker_name">阿姨姓名</label>';
    echo '<div class="col-sm-9"><input id="worker-worker_name" class="form-control" type="text" disabled="" value="'.$workerModel->worker_name.'" ></div>';
    echo '<div class="col-sm-offset-3 col-sm-9"></div>';
    echo '<div class="col-sm-offset-3 col-sm-9"><div class="help-block"></div></div>';
    echo '</div>';

    echo $form->field($workerModel, 'worker_blacklist_reason');
    echo $form->field($workerModel, 'worker_is_blacklist')->radioList([ '1' => '开启','0' => '关闭'], ['inline' => true]);

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