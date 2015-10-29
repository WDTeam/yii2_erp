<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var dbbase\models\CustomerComment $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="customer-comment-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'order_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单ID...', 'maxlength'=>10]], 

'customer_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 用户ID...', 'maxlength'=>10]], 

'customer_comment_phone'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 用户电话...', 'maxlength'=>11]], 

'customer_comment_star_rate'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 评论星级,0为评价,1-5星...']], 

'customer_comment_anonymous'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 是否匿名评价,0匿名,1非匿名...']], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...', 'maxlength'=>10]], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 更新时间...', 'maxlength'=>10]], 

'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 删除...']], 

'customer_comment_content'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 评论内容...', 'maxlength'=>255]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
