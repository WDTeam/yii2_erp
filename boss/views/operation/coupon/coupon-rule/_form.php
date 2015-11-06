<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var dbbase\models\operation\coupon\CouponRule $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="coupon-rule-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'couponrule_classify'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 1 一码一用  2 一码多用...']], 

'couponrule_category'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券分类0为一般优惠券1为赔付优惠券...']], 

'couponrule_type'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券类型0为全网优惠券1为类别优惠券2为商品优惠券...']], 

'couponrule_service_type_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务类别id...']], 

'couponrule_commodity_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 如果是商品优惠券id...']], 

'couponrule_city_limit'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 城市限制0为不限1为单一城市限制...']], 

'couponrule_city_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 关联城市...']], 

'couponrule_customer_type'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 适用客户类别逗号分割0为所有用户1为新用户2为老用户3会员4为非会员...']], 

'couponrule_get_start_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券的用户可领取开始时间...']], 

'couponrule_get_end_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券的用户可领取结束时间...']], 

'couponrule_use_start_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券的用户可使用的开始时间...']], 

'couponrule_use_end_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券的用户可使用的结束时间...']], 

'couponrule_use_end_days'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 领取后过期天数...']], 

'couponrule_promote_type'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券优惠类型0为立减1为满减2为每减...']], 

'couponrule_code_num'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠码个数...']], 

'couponrule_code_max_customer_num'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 如果是一码多用单个优惠码最大使用人数限制...']], 

'is_disabled'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 是否禁用...']], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...']], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 更新时间...']], 

'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 是否逻辑删除...']], 

'system_user_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠码创建人id...']], 

'couponrule_order_min_price'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 满减或每减时订单最小金额...', 'maxlength'=>8]], 

'couponrule_price'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券单价...', 'maxlength'=>8]], 

'couponrule_price_sum'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券总价...', 'maxlength'=>8]], 

'couponrule_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券名称...', 'maxlength'=>100]], 

'couponrule_category_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券范畴...', 'maxlength'=>100]], 

'couponrule_type_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券类型名称...', 'maxlength'=>100]], 

'couponrule_service_type_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务类别名称...', 'maxlength'=>100]], 

'couponrule_commodity_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 如果是商品名称...', 'maxlength'=>100]], 

'couponrule_customer_type_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 适用客户类别名称...', 'maxlength'=>100]], 

'couponrule_channelname'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 渠道名称(主要使用到一码多用分渠道发)...', 'maxlength'=>80]], 

'couponrule_city_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 城市名称...', 'maxlength'=>60]], 

'couponrule_promote_type_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券优惠类型名称...', 'maxlength'=>60]], 

'couponrule_code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 如果是1码多用的优惠码...', 'maxlength'=>40]], 

'system_user_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠码创建人...', 'maxlength'=>40]], 

'couponrule_Prefix'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠码前缀...', 'maxlength'=>20]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
