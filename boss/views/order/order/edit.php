<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use dosamigos\datepicker\DatePicker;

/**
 * @var yii\web\View $this
 * @var common\models\Order $model
 */

$this->title = '订单详情';
$this->params['breadcrumbs'][] = ['label' => '订单管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-create">
    <div class="order-form">
        <?php if($model->hasErrors()):?>
        <div class="alert alert-danger" role="alert"><?=Html::errorSummary($model); ?></div>
        <?php endif;?>
        <?php $form = ActiveForm::begin(['layout'=>'horizontal']); ?>

        <div class="panel panel-info">
           <div class="panel-heading">
                <h3 class="panel-title">支付信息</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label col-sm-3">订单金额</label>
                    <div class="col-sm-6" style="font-size: 15px;"><?= Html::encode($model->order_money) ?>元</div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">优惠券</label>
                    <div class="col-sm-6" style="font-size: 15px;"><?= Html::encode($model->order_use_coupon_money) ?>元</div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">需支付</label>
                    <div class="col-sm-6" style="font-size: 15px;"><?= Html::encode($model->order_pay_money) ?>元</div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">付款方式</label>
                    <div class="col-sm-6" style="font-size: 15px;"><?= Html::encode($model->order_pay_channel_name) ?></div>
                </div>
            </div>
           <div class="panel-heading">
                <h3 class="panel-title">用户信息</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label col-sm-3">用户电话</label>
                    <div class="col-sm-6" style="font-size: 15px;"><?= Html::encode($model->order_customer_phone) ?></div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">用户地址</label>
                    <div class="col-sm-6" style="font-size: 15px;"><?= Html::encode($model->order_address) ?></div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">服务类型</label>
                    <div class="col-sm-6" style="font-size: 15px;"><?= Html::encode($model->order_service_type_name) ?></div>
                </div>
            </div>
           <div class="panel-heading service-info-view">
                <h3 class="panel-title">服务信息</h3>
                <div class="pull-right">
                    <button class="btn btn-warning" type="button">修改</button>
                </div>                
            </div>
            <div class="panel-body service-info-view">
                <div class="form-group">
                    <label class="control-label col-sm-3">指定阿姨</label>
                    <div class="col-sm-6" style="font-size: 15px;"><?= Html::encode($model->order_worker_name) ?></div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">服务时间</label>
                    <div class="col-sm-6" style="font-size: 15px;"><?= date('Y-m-d H:i', $model->order_booked_begin_time) ?> ~ <?= date('Y-m-d H:i', $model->order_booked_end_time) ?></div>
                </div>
            </div> 
            <div class="panel-heading service-info-edit">
                <h3 class="panel-title">服务信息</h3>
            </div>
            <div class="panel-body service-info-edit">
                <?= $form->field($model, 'order_booked_worker_id')->inline()->radioList(['0'=>'不指定']); ?>
                <?= $form->field($model, 'orderBookedDate')->label('服务日期')->widget(
                    DatePicker::className(), [
                    'inline' => true,
                    'template' => '<div class="well well-sm" style="background-color: #fff; width:250px;font-size:14px;">{input}</div>',
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'startDate' => date('Y-m-d'),
                    ],
                    'language'=>'zh-CN'
                ]);?>
                <?= $form->field($model, 'order_booked_count')->inline()->radioList($model->orderBookedCountList)->label('服务时长'); ?>
                <?= $form->field($model, 'orderBookedTimeRange')->inline()->radioList($model->orderBookedTimeRangeList)->label('服务时间');?>
            </div>
            <div class="panel-heading">
                <h3 class="panel-title">支付信息</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class=" col-sm-3"></label>
                    <h4 class="col-sm-2">
                        单价：<span id="order_unit_money" style="font-size: 25px;color: #00ff00;" >25.00</span>
                    </h4>
                    <h4 class="col-sm-2">
                        总价：<span class="order_money" style="font-size: 25px;color: #ff0000;">50.00</span>
                    </h4>
                    <h4 class="col-sm-2">
                        账户余额：<span id="customer_balance" style="font-size: 25px;">0.00</span>
                    </h4>
                </div>
                <div style="display: none;"><?= $form->field($model, 'order_unit_money')->textInput(['maxlength' => true,'value'=>25]) ?></div>
                <div style="display: none;"><?= $form->field($model, 'order_money')->textInput(['maxlength' => true,'value'=>50]) ?></div>
                <?= $form->field($model, 'order_pay_type')->inline()->radioList(['1'=>'现金支付','2'=>'余额支付','3'=>'第三方预付'])->label('支付方式'); ?>
                <div id="order_pay_type_1" >
                    <div class="form-group">
                        <label class="control-label col-sm-3">需支付</label>
                        <div class="col-sm-2">
                            <span class="order_money" style="font-size: 15px;color: #ff0000;">50.00</span>
                        </div>
                    </div>
                </div>
                <div id="order_pay_type_2" style="display:none;">
                <?= $form->field($model, 'coupon_id')->dropDownList([""=>"请选择优惠券"],['maxlength' => true]) ?>
                <div class="form-group">
                    <label class="control-label col-sm-3">需支付</label>
                    <div class="col-sm-2">
                        <span class="order_pay_money" style="font-size: 15px;color: #ff0000;">50.00</span>
                    </div>
                </div>
                </div>
                <div id="order_pay_type_3" style="display:none;">
                <?= $form->field($model, 'channel_id')->inline()->radioList($model->orderChannelList); ?>
                <?= $form->field($model, 'order_pop_group_buy_code')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_pop_order_code')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_pop_order_money')->textInput(['maxlength' => true]) ?>
                </div>
            </div>                       
           <div class="panel-heading">
                <h3 class="panel-title">客户需求</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label col-sm-3">客户需求</label>
                    <div class="col-sm-6" style="font-size: 15px;"><?= Html::encode($model->order_customer_need) ?></div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">客户备注</label>
                    <div class="col-sm-6" style="font-size: 15px;"><?= Html::encode($model->order_customer_memo) ?></div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">客服备注</label>
                    <div class="col-sm-6" style="font-size: 15px;"><?= Html::encode($model->order_cs_memo) ?></div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">系统指派</label>
                    <div class="col-sm-6" style="font-size: 15px;"><?= $model->order_flag_sys_assign == 1 ? '是' : '否' ?></div>
                </div>
            </div>
            <div class="panel-heading">
                <h3 class="panel-title">客户需求</h3>
            </div>
            <div class="panel-body">
                <?= $form->field($model, 'order_customer_need')->inline()->checkboxList($model->customerNeeds) ?>
                <?= $form->field($model, 'order_customer_memo')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_cs_memo')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_flag_sys_assign')->inline()->radioList([1=>'是',0=>'否'])->label('系统指派'); ?>
            </div>                       
            <div class="panel-footer">
                <div class="form-group">
                    <div class="col-sm-offset-0 col-sm-12">
                        <?= Html::submitButton('提交更改', ['class' =>'btn btn-warning btn-lg btn-block']); ?>
                    </div>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
<?php
$this->registerJsFile('/js/order.js',['depends'=>[ 'yii\web\YiiAsset','yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('/js/order_edit.js',['depends'=>[ 'yii\web\YiiAsset','yii\bootstrap\BootstrapAsset']]);
$this->registerCss('
 #order-orderbookedtimerange .radio-inline ,#order-channel_id .radio-inline {
        margin-left: 0px;
        margin-right: 10px;
        margin-top: 0;
    }
    label,input.form-control,select.form-control{
        font-size:14px;
    }
    .radio{
        overflow:hidden;
        padding-bottom:7px;
    }
');
?>
