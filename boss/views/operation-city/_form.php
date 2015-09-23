<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use boss\components\AreaCascade;

/**
 * @var yii\web\View $this
 * @var common\models\OperationCity $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-city-form">

    <?php
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); 
    echo '<fieldset id="w2"><div class="form-group field-operationcity-operation_city_name required">';
    echo '<label class="control-label col-md-2" for="operationcity-operation_city_name">选择城市</label>';
    echo '<div class="col-md-10">'.Yii::$app->areacascade->cascadeAll('OperationCity', ['class' => 'form-control']).'</div>';
    echo '</div></fieldset>';
    
    echo Form::widget(['model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
        'operation_city_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'城市名称', 'maxlength'=>30]],
    ]
    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
