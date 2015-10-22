<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\coupon\Coupon $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="coupon-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'coupon_price'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券价值...', 'maxlength'=>8]], 

'coupon_order_min_price'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 满减或每减时订单最小金额...', 'maxlength'=>8]], 

'coupon_type'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券类型0为全网优惠券1为类别优惠券2为商品优惠券...']], 

'coupon_service_type_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务类别id...']], 

'coupon_service_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务id...']], 

'coupon_city_limit'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 城市限制0为不限1为单一城市限制...']], 

'coupon_city_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 关联城市...']], 

'coupon_customer_type'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 适用客户类别逗号分割0为所有用户1为新用户2为老用户3会员4为非会员...']], 

'coupon_time_type'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券有效时间类型0为有效领取时间和有效使用时间一致1为过期时间从领取时间开始计算...']], 

'coupon_begin_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 开始时间...']], 

'coupon_end_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 领取时间和使用时间一致时的结束时间...']], 

'coupon_get_end_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 领取时间和使用时间不一致时的领取结束时间...']], 

'coupon_use_end_days'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 领取时间和使用时间不一致时过期天数...']], 

'coupon_promote_type'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券优惠类型0为立减1为满减2为每减...']], 

'coupon_code_num'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠码个数...']], 

'coupon_code_max_customer_num'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 单个优惠码最大使用人数...']], 

'is_disabled'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 是否禁用...']], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...']], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 更新时间...']], 

'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 是否逻辑删除...']], 

'system_user_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 系统用户id...']], 

'coupon_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券名称...', 'maxlength'=>255]], 

'coupon_type_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券类型名称...', 'maxlength'=>255]], 

'coupon_service_type_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务类别名称...', 'maxlength'=>255]], 

'coupon_service_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务名称...', 'maxlength'=>255]], 

'coupon_city_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 城市名称...', 'maxlength'=>255]], 

'coupon_customer_type_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 适用客户类别名称...', 'maxlength'=>255]], 

'coupon_promote_type_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券优惠类型名称...', 'maxlength'=>255]], 

'system_user_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 系统用户名称...', 'maxlength'=>255]], 

'coupon_time_type_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券有效时间类型名称...', 'maxlength'=>4]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
