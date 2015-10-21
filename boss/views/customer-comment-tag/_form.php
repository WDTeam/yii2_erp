<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\CustomerCommentTag $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="customer-comment-tag-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'customer_comment_tag_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 评价标签名称...', 'maxlength'=>255]], 

'customer_comment_level'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 评价等级...']], 

'is_online'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 是否上线...']], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...']], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 更新时间...']], 

'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 删除...']], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
