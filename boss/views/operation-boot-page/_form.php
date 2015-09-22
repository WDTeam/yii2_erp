<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\OperationBootPage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="operation-boot-page-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'operation_boot_page_name')->textInput(['maxlength' => 60]) ?>

    <?= $form->field($model, 'operation_boot_page_ios_img')->fileInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operation_boot_page_android_img')->fileInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operation_boot_page_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operation_boot_page_residence_time')->textInput() ?>


   <!-- <?= $form->field($model, 'operation_boot_page_online_time')->textInput() ?>-->

    <!-- <?= $form->field($model, 'operation_boot_page_offline_time')->textInput() ?>-->

    <?php
    echo '<label class="control-label">'.$model->attributeLabels()['operation_boot_page_online_time'].'</label>';
    echo DatePicker::widget([
        'name' => 'OperationBootPage[operation_boot_page_online_time]',
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
        'value' => !empty($model->operation_boot_page_online_time) ? date('Y-m-d', $model->operation_boot_page_online_time) : '',
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);
    ?>
    <?php
    echo '<label class="control-label">'.$model->attributeLabels()['operation_boot_page_offline_time'].'</label>';
    echo DatePicker::widget([
        'name' => 'OperationBootPage[operation_boot_page_offline_time]',
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
        'value' => !empty($model->operation_boot_page_offline_time) ? date('Y-m-d', $model->operation_boot_page_offline_time) : '',
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);
    ?>


    <!--
<?//= $form->field($model, 'created_at')->textInput() ?>

<?//= $form->field($model, 'updated_at')->textInput() ?>
-->
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
