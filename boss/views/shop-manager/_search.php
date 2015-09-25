<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use boss\components\AreaCascade;

/**
 * @var yii\web\View $this
 * @var boss\models\search\ShopManagerSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="shop-manager-search row">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="col-md-3">
        <label class="control-label" for="workersearch-worker_work_city">所在城市</label>
        <div>
        <?php echo AreaCascade::widget([
            'model' => $model,
            'options' => ['class' => 'form-control inline'],
            'label' =>'选择城市',
            'grades' => 'city',
            'is_minui'=>true,
        ]);?>
        </div>
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

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

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
