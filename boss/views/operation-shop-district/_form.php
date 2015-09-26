<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var boss\models\Operation\OperationShopDistrict $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-shop-district-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
        'operation_shop_district_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'商圈名称...', 'maxlength'=>60]],
    ]


    ]);
    ?>
    <?= $form->field($OperationShopDistrictCoordinate, 'operation_shop_district_coordinate_start_longitude')->textInput() ?>
    <?= $form->field($OperationShopDistrictCoordinate, 'operation_shop_district_coordinate_start_latitude')->textInput() ?>
    <?= $form->field($OperationShopDistrictCoordinate, 'operation_shop_district_coordinate_end_longitude')->textInput() ?>
    <?= $form->field($OperationShopDistrictCoordinate, 'operation_shop_district_coordinate_end_latitude')->textInput() ?>
<?php
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
