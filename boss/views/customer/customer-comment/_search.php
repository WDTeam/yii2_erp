<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var boss\models\CustomerCommentSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="customer-comment-search">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_VERTICAL,
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class='col-md-2'>
        <?php echo $form->field($model, 'customer_comment_phone')->label('客户手机'); ?>
    </div>

    <div class='col-md-2'>
        <?php echo $form->field($model, 'customer_comment_content')->label('评论内容'); ?>
    </div>

    <div class='col-md-2'>
        <?php echo $form->field($model, 'customer_comment_anonymous')->widget(Select2::classname(), [
            'name' => 'customer_comment_anonymous',
            'hideSearch' => true,
            'data' => [1 => '匿名', 0 => '非匿名'],
            'options' => ['placeholder' => '选择评价方式', 'inline' => true],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
    </div>

    <?php // $form->field($model, 'id') ?>

    <?php // $form->field($model, 'order_id') ?>

    <?php // $form->field($model, 'customer_id') ?>

    <?php // $form->field($model, 'customer_comment_phone') ?>

    <?php // $form->field($model, 'customer_comment_content') ?>

    <?php // echo $form->field($model, 'customer_comment_star_rate') ?>

    <?php // echo $form->field($model, 'customer_comment_anonymous') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <div class="form-group">
        <div class='col-md-2' style="margin-top: 22px;">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
