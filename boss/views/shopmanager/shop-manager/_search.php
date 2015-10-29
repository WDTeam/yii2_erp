<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use boss\components\AreaCascade;
use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var core\models\shop\ShopManagerSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="shop-manager-search row">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="col-md-4">
        <?= $form->field($model, 'city_id')->widget(Select2::classname(), [
            'name' => 'city_id',
            'hideSearch' => true,
            'data' => $model::getOnlineCityList(),
            'options' => ['placeholder' => '选择城市...', 'inline' => true],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
    </div>
    
    <div class="col-md-3">
    <?= $form->field($model, 'name')->label('中介名称、负责人姓名、电话等') ?>
    </div>


    <?php // echo $form->field($model, 'street') ?>

    <?php // echo $form->field($model, 'principal') ?>

    <?php // echo $form->field($model, 'tel') ?>

    <?php // echo $form->field($model, 'other_contact') ?>

    <?php // echo $form->field($model, 'bankcard_number') ?>

    <?php // echo $form->field($model, 'account_person') ?>

    <?php // echo $form->field($model, 'opening_bank') ?>

    <?php // echo $form->field($model, 'sub_branch') ?>

    <?php // echo $form->field($model, 'opening_address') ?>

    <?php // echo $form->field($model, 'bl_name') ?>

    <?php // echo $form->field($model, 'bl_type') ?>

    <?php // echo $form->field($model, 'bl_number') ?>

    <?php // echo $form->field($model, 'bl_person') ?>

    <?php // echo $form->field($model, 'bl_address') ?>

    <?php // echo $form->field($model, 'bl_create_time') ?>

    <?php // echo $form->field($model, 'bl_photo_url') ?>

    <?php // echo $form->field($model, 'bl_audit') ?>

    <?php // echo $form->field($model, 'bl_expiry_start') ?>

    <?php // echo $form->field($model, 'bl_expiry_end') ?>

    <?php // echo $form->field($model, 'bl_business') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'is_blacklist') ?>

    <?php // echo $form->field($model, 'blacklist_time') ?>

    <?php // echo $form->field($model, 'blacklist_cause') ?>

    <?php // echo $form->field($model, 'audit_status') ?>

    <?php // echo $form->field($model, 'shop_count') ?>

    <?php // echo $form->field($model, 'worker_count') ?>

    <?php // echo $form->field($model, 'complain_coutn') ?>

    <?php // echo $form->field($model, 'level') ?>

    <div class="col-md-2" style="margin-top:22px;">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
