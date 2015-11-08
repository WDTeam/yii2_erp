<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var dbbase\models\operation\coupon\CouponUserinfo $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="coupon-userinfo-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
     echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' =>1,
    'attributes' => [
     		
  'coupon_rule_name_id'=>[
     		'type' => Form::INPUT_DROPDOWN_LIST,
     		'items' => ['1'=>'规则一','2'=>'规则二','3'=>'规则三','4'=>'规则四',],
     		'options' => [
     		'prompt' => '请选择优惠券规则',
     		],
    		],
'customer_tel'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'请输入客户手机号...如果是多个请使用 tel1|tel2|tel3....等输入']], 
    ]
    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
