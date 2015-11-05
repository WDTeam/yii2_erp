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

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'customer_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 客户id...']], 

'coupon_userinfo_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠规则id...']], 

'coupon_userinfo_gettime'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 领取时间...']], 

'coupon_userinfo_usetime'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 使用时间...']], 

'coupon_userinfo_endtime'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 过期时间...']], 

'system_user_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 绑定人id...']], 

'is_used'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 是否已经使用...']], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...']], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 更新时间...']], 

'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 是否逻辑删除...']], 

'coupon_userinfo_price'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券价值...', 'maxlength'=>8]], 

'customer_tel'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 客户手机号...', 'maxlength'=>11]], 

'coupon_userinfo_code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠码...', 'maxlength'=>40]], 

'system_user_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 绑定人名称...', 'maxlength'=>40]], 

'coupon_userinfo_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券名称...', 'maxlength'=>100]], 

'order_code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 如果已经使用订单号...', 'maxlength'=>64]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
