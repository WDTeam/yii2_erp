<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
/**
 * @var yii\web\View $this
 * @var boss\models\operation\coupon\CouponUserinfo $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="coupon-userinfo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
<!-- system_user_id -->
    
    <div class='col-md-2'>
        <?php echo $form->field($model, 'system_user_id')->widget(Select2::classname(), [
            'name' => 'system_user_id',
            'hideSearch' => true,
            'data' => [0 => '用户领取', 1 =>'后台绑定'],
            'options' => ['placeholder' => '选择用户类型', 'inline' => true],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
    </div>
    
    
   

  <div class='col-md-2'>  

    <?= $form->field($model, 'customer_tel') ?>
</div>

<div class='col-md-2'>  
    <?= $form->field($model, 'coupon_userinfo_code') ?>
</div>

    
<div class='col-md-2'>
    <?php  echo $form->field($model, 'order_code') ?>
    </div>
<div class='col-md-2'>
    <?php  echo $form->field($model, 'system_user_name') ?>
</div>
   
    <div class="form-group">
    <div class='col-md-2' style="margin-top: 22px;">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>
 </div>
    <?php ActiveForm::end(); ?>

</div>
