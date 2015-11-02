<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var boss\models\shop\ShopCustomeRelationSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="shop-custome-relation-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'system_user_id') ?>

    <?= $form->field($model, 'baseid') ?>

    <?= $form->field($model, 'shopid') ?>

    <?= $form->field($model, 'shop_manager_id') ?>

    <?php // echo $form->field($model, 'stype') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
