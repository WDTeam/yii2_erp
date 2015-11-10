<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\builder\FormGrid;
use dbbase\models\WorkerBlock;
use yii\bootstrap\Modal;
use core\models\worker\Worker;
use kartik\widgets\FileInput;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
?>

<?php
$model = new \boss\models\worker\WorkerExport();
Modal::begin(['header' => '<h4 class="modal-title">导入阿姨信息</h4>', 'toggleButton' => ['label' => '<i ></i>导入阿姨信息', 'class' => 'btn btn-default']]);
?>
<div class="">

<?php
    $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL],
        'action'=>['export-data-from-excel'],
        'options'=>[
            'style'=>'height:100px',
            'enctype' => 'multipart/form-data'
        ],
    ]);
    echo $form->field($model, 'excel')->fileInput(['maxlength' => true]) ;
    echo Html::submitButton('确认',['class'=>'btn btn-primary btn-lg btn-block']);
    ActiveForm::end();
?>

</div>
<?php
Modal::end();
?>
