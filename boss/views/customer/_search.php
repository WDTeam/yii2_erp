<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;
use kartik\date\DatePicker;

/**
 * @var yii\web\View $this
 * @var boss\models\Customer $model
 * @var yii\widgets\ActiveForm $form
 */
// $url = \yii\helpers\Url::to(['index']);
?>

<div class="customer-search">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_VERTICAL,
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php
    echo $form->field($model, 'region_id')->widget(Select2::classname(), [
        'initValueText' => '城市',
        // 'name' => 'region_id',
        'hideSearch' => true,
        'data' => [1 => '北京', 2 => '上海', 3 => '成都', 4 => '深圳'],
        'options' => ['placeholder' => '选择城市', 'inline' => true],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); 
    ?>

    <?php //echo $form->field($model, 'id') ?>

    <?php echo $form->field($model, 'customer_name') ?>

    <?php //echo $form->field($model, 'customer_sex') ?>

    <?php //echo $form->field($model, 'customer_birth') ?>

    <?php //echo $form->field($model, 'customer_photo') ?>

    <?php echo $form->field($model, 'customer_phone') ?>

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
        <?= Html::submitButton(Yii::t('boss', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('boss', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
