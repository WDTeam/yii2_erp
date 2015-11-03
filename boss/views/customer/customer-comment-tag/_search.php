<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
/**
 * @var yii\web\View $this
 * @var boss\models\CustomerCommentTagSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="customer-comment-tag-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

 
        <div class='col-md-2'>
    <?= $form->field($model, 'is_online')->widget(Select2::classname(), [
        'name' => '状态',
        'hideSearch' => true,		
        'data' => ['1'=>'开启','0'=>'关闭'],
        'options' => ['placeholder' => '选择状态','class' => 'col-md-2'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
   
    ?>
     </div>
     
      <div class='col-md-2'>
     
    <?= $form->field($model, 'customer_tag_name') ?>
</div>


   <div class="form-group">
    <div class='col-md-2' style="    margin-top: 22px;">
        <?= Html::submitButton(Yii::t('boss', '查询'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('boss', '重置'), ['class' => 'btn btn-default']) ?>
    </div>
  </div>
    <?php ActiveForm::end(); ?>

</div>
