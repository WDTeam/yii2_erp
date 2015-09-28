<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var core\models\Order\Order $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'order_code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单号...', 'maxlength'=>64]], 

'order_service_type_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单服务类别ID...']], 

'customer_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 用户编号...', 'maxlength'=>10]], 

'order_ip'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 下单IP...']], 

'address_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 地址ID...', 'maxlength'=>10]], 

'order_unit_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单单位价格...', 'maxlength'=>8]], 

'order_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单金额...', 'maxlength'=>8]], 

'order_before_status_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 状态变更前订单状态...', 'maxlength'=>128]], 

'order_status_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单状态...', 'maxlength'=>128]], 

'order_service_type_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单服务类别...', 'maxlength'=>128]], 

'channel_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单渠道ID...', 'maxlength'=>10]], 

'order_src_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单来源，订单入口id...']], 

'order_src_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单来源，订单入口名称...', 'maxlength'=>128]], 

'order_address'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 详细地址 包括 联系人 手机号...', 'maxlength'=>255]], 

'order_customer_phone'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 用户手机号...', 'maxlength'=>16]], 

'order_booked_begin_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 预约开始时间...', 'maxlength'=>11]], 

'order_booked_end_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 预约结束时间...', 'maxlength'=>11]], 

'order_booked_date'=>['type'=> TabularForm::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 预约服务日期...']], 

'order_booked_time_range'=>['type'=> TabularForm::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 预约服务时间...']], 

'order_parent_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 父级id...', 'maxlength'=>20]], 

'order_is_parent'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 有无子订单 1有 0无...']], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 创建时间...', 'maxlength'=>11]], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 修改时间...', 'maxlength'=>11]], 

'order_before_status_dict_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 状态变更前订单状态字典ID...']], 

'order_status_dict_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单状态字典ID...']], 

'order_flag_send'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 指派不了 0可指派 1客服指派不了 2小家政指派不了 3都指派不了...']], 

'order_flag_urgent'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 加急...']], 

'order_flag_exception'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 异常 1无经纬度...']], 

'order_flag_sys_assign'=>['type'=> TabularForm::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 是否需要系统指派 1是 0否...']], 

'order_booked_count'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 预约服务数量...', 'maxlength'=>10]], 

'order_booked_worker_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 指定阿姨...', 'maxlength'=>10]], 

'order_pay_type'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付方式 0未支付 1现金支付 2线上支付 3第三方预付 ...']], 

'pay_channel_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付渠道id...', 'maxlength'=>10]], 

'card_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 服务卡ID...', 'maxlength'=>11]], 

'coupon_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 优惠券ID...', 'maxlength'=>11]], 

'promotion_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 促销id...', 'maxlength'=>10]], 

'order_lock_status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 是否锁定 1锁定 0未锁定...']], 

'worker_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨id...', 'maxlength'=>10]], 

'worker_type_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨职位类型ID...', 'maxlength'=>10]], 

'order_worker_assign_type'=>['type'=> TabularForm::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨接单方式 0未接单 1阿姨抢单 2客服指派 3门店指派...']], 

'shop_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 门店id...', 'maxlength'=>10]], 

'comment_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 评价id...', 'maxlength'=>10]], 

'order_customer_hidden'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 客户端是否已删除...']], 

'invoice_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 发票id...', 'maxlength'=>10]], 

'checking_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 对账id...', 'maxlength'=>10]], 

'admin_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 操作人id  0客户操作 1系统操作...', 'maxlength'=>10]], 

'isdel'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 是否已删除...']], 

'order_pop_operation_money'=>['type'=> TabularForm::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 第三方运营费...']], 

'order_pop_order_money'=>['type'=> TabularForm::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 第三方订单金额...']], 

'order_pay_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付金额...', 'maxlength'=>8]], 

'order_use_acc_balance'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 使用余额...', 'maxlength'=>8]], 

'order_use_card_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 使用服务卡金额...', 'maxlength'=>8]], 

'order_use_coupon_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 使用优惠卷金额...', 'maxlength'=>8]], 

'order_use_promotion_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 使用促销金额...', 'maxlength'=>8]], 

'order_pop_pay_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 合作方结算金额 负数表示合作方结算规则不规律无法计算该值。...', 'maxlength'=>8]], 

'order_channel_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 订单渠道名称...', 'maxlength'=>64]], 

'order_worker_type_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨职位类型...', 'maxlength'=>64]], 

'order_pay_channel_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付渠道名称...', 'maxlength'=>128]], 

'order_pop_order_code'=>['type'=> TabularForm::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 第三方订单编号...']], 

'order_pop_group_buy_code'=>['type'=> TabularForm::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 第三方团购码...']], 

'order_customer_need'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 用户需求...', 'maxlength'=>255]], 

'order_customer_memo'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 用户备注...', 'maxlength'=>255]], 

'order_cs_memo'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 客服备注...', 'maxlength'=>255]], 

'order_pay_flow_num'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 支付流水号...', 'maxlength'=>255]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
