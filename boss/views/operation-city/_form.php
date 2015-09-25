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
    
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL, 'options' => ['enctype' => 'multipart/form-data']]); 
    echo AreaCascade::widget([
        'model' => $model,
        'options' => ['class' => 'form-control'],
        'label' =>'选择城市',
        'grades' => 'city',
    ]);
    echo Html::fileInput('file');
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
