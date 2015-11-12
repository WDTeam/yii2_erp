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
        'action' => ['view', 'city_id' => $city_id],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-sm-2">
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
            <?= Html::submitButton(Yii::t('app', 'Search'), [
                'class' => 'btn btn-primary',
                'style' => 'margin-top:17px',
            ]) ?>
            <?= Html::a(Yii::t('app', '保存排序'), 'javascript:void(0);', [
                'class' => 'btn btn-primary',
                'id' => 'saveReleaseAdvOrders',
                'style' => 'margin-top:17px',
            ]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
