<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var boss\models\search\ShopSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="shop-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'shop_menager_id') ?>

    <?= $form->field($model, 'province_name') ?>

    <?= $form->field($model, 'city_name') ?>

    <?php // echo $form->field($model, 'county_name') ?>

    <?php // echo $form->field($model, 'street') ?>

    <?php // echo $form->field($model, 'principal') ?>

    <?php // echo $form->field($model, 'tel') ?>

    <?php // echo $form->field($model, 'other_contact') ?>

    <?php // echo $form->field($model, 'bankcard_number') ?>

    <?php // echo $form->field($model, 'account_person') ?>

    <?php // echo $form->field($model, 'opening_bank') ?>

    <?php // echo $form->field($model, 'sub_branch') ?>

    <?php // echo $form->field($model, 'opening_address') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <?php // echo $form->field($model, 'is_blacklist') ?>

    <?php // echo $form->field($model, 'blacklist_time') ?>

    <?php // echo $form->field($model, 'blacklist_cause') ?>

    <?php // echo $form->field($model, 'audit_status') ?>

    <?php // echo $form->field($model, 'worker_count') ?>

    <?php // echo $form->field($model, 'complain_coutn') ?>

    <?php // echo $form->field($model, 'level') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
