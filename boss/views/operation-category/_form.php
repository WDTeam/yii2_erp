<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\OperationCategory $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="operation-category-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); ?>

    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">基础信息</h3>
        </div>
        <div class="panel-body">
            <?php
            echo Form::widget([

            'model' => $model,
            'form' => $form,
            'columns' => 1,
            'attributes' => [
                'operation_category_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'输入服务品类名称...', 'maxlength'=>60]],
            ]


            ]); ?>
        </div>
        <div class="panel-footer">
            <div class="form-group">
                <div class="col-sm-offset-0 col-sm-12">
                    <?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-lg btn-block' : 'btn btn-primary btn-lg btn-block']); ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
