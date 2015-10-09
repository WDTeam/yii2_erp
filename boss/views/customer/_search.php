<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use boss\components\AreaCascade;

/**
 * @var yii\web\View $this
 * @var boss\models\Customer $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="customer-search">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_VERTICAL,
        'action' => ['customer/index'],
        'method' => 'get',
    ]); ?>

    <div class='col-md-2'>
        <?php echo $form->field($model, 'operation_city_id')->widget(Select2::classname(), [
            'name' => 'operation_city_id',
            'hideSearch' => true,
            'data' => [1 => '北京', 2 => '上海', 3 => '成都', 4 => '深圳'],
            'options' => ['placeholder' => '选择城市', 'inline' => true],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
    </div>

   <div class='col-md-2'>
        <?php echo $form->field($model, 'time_begin')->widget(DatePicker::classname(), [
            'name' => 'time_begin', 
            'value' => time(),
            'options' => ['placeholder' => '选择日期'],
            'pluginOptions' => [
                'format' => 'yyyy-m-d',
                'todayHighlight' => true
            ]
        ]); 
        ?>
    </div>

    <div class='col-md-2'>
        <?php echo $form->field($model, 'time_end')->widget(DatePicker::classname(), [
            'name' => 'time_end', 
            'value' => time(),
            'options' => ['placeholder' => '选择日期'],
            'pluginOptions' => [
                'format' => 'yyyy-m-d',
                'todayHighlight' => true
            ]
        ]); 
        ?>
    </div>

    <div class='col-md-2'>
        <?php echo $form->field($model, 'customer_is_vip')->widget(Select2::classname(), [
            'name' => 'customer_is_vip',
            'hideSearch' => true,
            'data' => [1 => '会员', 2 => '非会员'],
            'options' => ['placeholder' => '选择客户身份', 'inline' => true],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
    </div>

    <div class='col-md-2'>
        <?php echo $form->field($model, 'customer_name')->label('客户姓名，电话等'); ?>
    </div>

    <?php //echo $form->field($model, 'id') ?>

    <?php //echo $form->field($model, 'customer_name') ?>

    <?php //echo $form->field($model, 'customer_sex') ?>

    <?php //echo $form->field($model, 'customer_birth') ?>

    <?php //echo $form->field($model, 'customer_photo') ?>

    <?php //echo $form->field($model, 'customer_phone') ?>

    <?php // echo $form->field($model, 'customer_email') ?>

    <?php // echo $form->field($model, 'region_id') ?>

    <?php // echo $form->field($model, 'customer_live_address_detail') ?>

    <?php // echo $form->field($model, 'customer_balance') ?>

    <?php // echo $form->field($model, 'customer_score') ?>

    <?php // echo $form->field($model, 'customer_level') ?>

    <?php // echo $form->field($model, 'customer_complaint_times') ?>

    <?php // echo $form->field($model, 'customer_src') ?>

    <?php // echo $form->field($model, 'channal_id') ?>

    <?php // echo $form->field($model, 'platform_id') ?>

    <?php // echo $form->field($model, 'customer_login_ip') ?>

    <?php // echo $form->field($model, 'customer_login_time') ?>

    <?php // echo $form->field($model, 'customer_is_vip') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <?php // echo $form->field($model, 'customer_del_reason') ?>

    <div class="form-group">
        <div class='col-md-2' style="margin-top: 22px;">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
