<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\OperationCategoryType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="operation-category-type-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <i class="hide"><?= $form->field($model, 'operation_category_id')->hiddenInput(['value' =>$model->operation_category_id ]) ?></i>

    <?= $form->field($model, 'operation_category_type_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operation_category_type_introduction')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'operation_category_type_english_name')->textInput(['maxlength' => true]) ?>

    <?php
        echo '<label class="control-label">'.$model->attributeLabels()['operation_category_type_start_time'].'</label>';
        echo DatePicker::widget([
        'name' => 'OperationCategoryType[operation_category_type_start_time]',
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
        'value' => $model->operation_category_type_start_time,
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ],
    ]);
    ?>
        
    <?php
        echo '<label class="control-label">'.$model->attributeLabels()['operation_category_type_end_time'].'</label>';
        echo DatePicker::widget([
        'name' => 'OperationCategoryType[operation_category_type_end_time]',
        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
        'value' => $model->operation_category_type_end_time,
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);
    ?>

    
    <?php //= $form->field($model, 'operation_category_type_start_time')->textInput(['maxlength' => true]) ?>

    <?php //= $form->field($model, 'operation_category_type_end_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operation_category_type_service_time_slot')->checkboxList(['8:00-10:00' => '8:00-10:00','10:00-12:00' => '10:00-12:00','13:00-15:00' => '13:00-15:00','15:00-17:00' => '15:00-17:00','17:00-19:00' => '17:00-19:00']) ?>

    <?= $form->field($model, 'operation_category_type_service_interval_time')->textInput() ?>

    <?= $form->field($model, 'operation_price_strategy_id')->dropDownList($priceStrategies)->label('选择价格策略') ?>
    <div id="hidePrice" class="hide">
        <?= $form->field($model, 'operation_category_type_price')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'operation_category_type_balance_price')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'operation_category_type_lowest_consume')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'operation_category_type_additional_cost')->textInput(['maxlength' => true]) ?>
    </div>
    <?= $form->field($model, 'operation_category_type_price_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'operation_category_type_market_price')->textInput(['maxlength' => true, 'placeholder' => '市场价格（单位：元）'])->label('市场价格（单位：元）') ?>

    <?= $form->field($model, 'operation_tags')->textInput(['placeholder' => '多个标签用逗号“,”分开'])->label('服务类型标签') ?>

    <?= $form->field($model, 'operation_category_type_app_ico')->fileInput() ?>

    <?= $form->field($model, 'operation_category_type_pc_ico')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
