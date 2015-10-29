<?php

use yii\helpers\Html; use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\OperationAdvertReleaseSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-advert-release-search">

    <?php $form = ActiveForm::begin([
        'action' => ['view'],
        'method' => 'get',
    ]); ?>


    <div class="col-md-2">
        <?= $form->field($model, 'city_name') ?>
    </div>

    <div class="col-md-2">
        <?= $form->field($model, 'operation_advert_content_name') ?>
    </div>


    <div class="col-md-2">
        <?= $form->field($model, 'platform_name') ?>
    </div>

    <div class="col-md-2">
        <?= $form->field($model, 'platform_version_name') ?>
    </div>

    <div class="col-md-2">
        <?= $form->field($model, 'position_name') ?>
    </div>

    <div class="form-group">

        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', '保存排序'), 'javascript:void(0);', ['class' => 'btn btn-primary', 'id' => 'saveReleaseAdvOrders']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
