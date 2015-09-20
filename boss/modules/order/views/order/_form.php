<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\Order $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'order_parent_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 父级id...', 'maxlength'=>20]], 

'order_is_parent'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 有无子订单 1有 0无...']], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...', 'maxlength'=>11]], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 修改时间...', 'maxlength'=>11]], 

'order_before_status_dict_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 状态变更前订单状态字典ID...']], 

'order_status_dict_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单状态字典ID...']], 

'order_service_type_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单服务类别ID...']], 

'order_src_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单来源，订单入口id...']], 

'channel_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 下单渠道ID...', 'maxlength'=>10]], 

'customer_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 用户编号...', 'maxlength'=>10]], 

'order_booked_begin_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 预约开始时间...', 'maxlength'=>11]], 

'order_booked_end_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 预约结束时间...', 'maxlength'=>11]], 

'order_booked_count'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 预约服务数量...', 'maxlength'=>10]], 

'address_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 地址ID...', 'maxlength'=>10]], 

'order_booked_worker_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 指定阿姨...', 'maxlength'=>10]], 

'order_pay_type'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付方式 0线上支付 1现金支付...']], 

'pay_channel_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付渠道id...', 'maxlength'=>10]], 

'card_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务卡ID...', 'maxlength'=>11]], 

'coupon_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券ID...', 'maxlength'=>11]], 

'promotion_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 促销id...', 'maxlength'=>10]], 

'worker_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨id...', 'maxlength'=>10]], 

'worker_type_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨职位类型ID...', 'maxlength'=>10]], 

'order_worker_distri_type'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨接单方式 0未接单 1阿姨抢单 2客服指派 3门店指派...']], 

'order_lock_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 是否锁定 1锁定 0未锁定...']], 

'comment_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 评价id...', 'maxlength'=>10]], 

'invoice_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 发票id...', 'maxlength'=>10]], 

'checking_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 对账id...', 'maxlength'=>10]], 

'shop_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 门店id...', 'maxlength'=>10]], 

'admin_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 操作人id...', 'maxlength'=>10]], 

'isdel'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 是否已删除...']], 

'order_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单金额...', 'maxlength'=>8]], 

'order_pay_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付金额...', 'maxlength'=>8]], 

'order_use_acc_balance'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 使用余额...', 'maxlength'=>8]], 

'order_use_card_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 使用服务卡金额...', 'maxlength'=>8]], 

'order_use_coupon_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 使用优惠卷金额...', 'maxlength'=>8]], 

'order_use_promotion_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 使用促销金额...', 'maxlength'=>8]], 

'order_worker_bonus_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 补贴金额...', 'maxlength'=>8]], 

'order_pop_pay_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 合作方结算金额 负数表示合作方结算规则不规律无法计算该值。...', 'maxlength'=>8]], 

'order_worker_bonus_detail'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter 补贴明细...','rows'=> 6]], 

'order_before_status_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 状态变更前订单状态...', 'maxlength'=>128]], 

'order_status_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单状态...', 'maxlength'=>128]], 

'order_service_type_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单服务类别...', 'maxlength'=>128]], 

'order_src_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单来源，订单入口名称...', 'maxlength'=>128]], 

'order_pay_channel_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付渠道名称...', 'maxlength'=>128]], 

'order_channel_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 下单渠道名称...', 'maxlength'=>64]], 

'order_worker_type_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨职位类型...', 'maxlength'=>64]], 

'order_channel_order_num'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 渠道订单编号...', 'maxlength'=>255]], 

'order_address'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 详细地址 包括 联系人 手机号...', 'maxlength'=>255]], 

'order_customer_need'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 用户需求...', 'maxlength'=>255]], 

'order_customer_memo'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 用户备注...', 'maxlength'=>255]], 

'order_cs_memo'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 客服备注...', 'maxlength'=>255]], 

'order_pay_flow_num'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付流水号...', 'maxlength'=>255]], 

'order_customer_phone'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 用户手机号...', 'maxlength'=>16]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
