<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var boss\models\OperationCategory $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-category-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>


    <div class="row">
        <div class="col-sm-2">
            <?= $form->field($model, 'operation_category_name') ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Search'), [
                'class' => 'btn btn-primary',
                'style' => 'margin-top:17px',
            ]) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), [
                'class' => 'btn btn-default',
                'style' => 'margin-top:17px',
            ]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
