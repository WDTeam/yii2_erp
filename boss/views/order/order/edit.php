<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use dosamigos\datepicker\DatePicker;

/**
 * @var yii\web\View $this
 * @var dbbase\models\Order $model
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
                <h3 class="panel-title">操作日志</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label col-sm-3"><?= date('Y-m-d H:i:s', $model->created_at)?></label>
                    <div class="col-sm-6 right-text"><?= Html::encode($history['creator_name']) ?> 创建订单，下单渠道：<?= Html::encode($model->order_channel_name) ?></div>
                </div>
                <?php if($history['pay_time']):?>
                <div class="form-group">
                    <label class="control-label col-sm-3"><?= Html::encode($history['pay_time']) ?></label>
                    <div class="col-sm-6 right-text"><?= Html::encode($model->order_pay_channel_name) ?> <?= Html::encode($model->order_pay_money) ?></div>
                </div>
                <?php endif;?>
        </div>        
        <div class="panel panel-info">
           <div class="panel-heading">
                <h3 class="panel-title">支付信息</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label col-sm-3">订单金额</label>
                    <div class="col-sm-6 right-text"><?= Html::encode($model->order_money) ?>元</div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">优惠券</label>
                    <div class="col-sm-6 right-text"><?= Html::encode($model->order_use_coupon_money) ?>元</div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">需支付</label>
                    <div class="col-sm-6 right-text"><?= Html::encode($model->order_pay_money) ?>元</div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">付款方式</label>
                    <div class="col-sm-6 right-text"><?= Html::encode($model->order_pay_channel_name) ?></div>
                </div>
            </div>
           <div class="panel-heading">
                <h3 class="panel-title">用户信息</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label col-sm-3">用户电话</label>
                    <div class="col-sm-6 right-text"><?= Html::encode($model->order_customer_phone) ?></div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">用户地址</label>
                    <div class="col-sm-6 right-text"><?= Html::encode($model->order_address) ?></div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">服务类型</label>
                    <div class="col-sm-6 right-text"><?= Html::encode($model->order_service_type_name) ?></div>
                </div>
            </div>
           <div class="panel-heading service-info-view">
                <h3 class="panel-title">服务信息</h3>
                <div class="pull-right" style="margin-top: -26px;">
<!--                     <button class="btn btn-warning btn-edit-service-info" type="button">修改</button> -->
                </div>                
            </div>
            <div class="panel-body service-info-view">
                <div class="form-group">
                    <label class="control-label col-sm-3">指定阿姨</label>
                    <div class="col-sm-6 right-text"><?= Html::encode($model->order_worker_name) ?></div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">服务时间</label>
                    <div class="col-sm-6 right-text"><?= date('Y-m-d H:i', $model->order_booked_begin_time) ?> ~ <?= date('Y-m-d H:i', $model->order_booked_end_time) ?></div>
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
                <div class="form-group">
                    <?= Html::submitButton('提交更改', ['class' =>'btn btn-warning']); ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <button class="btn btn-warning btn-cancel-service-info" type="button">取消修改</button>
                </div>
            </div>
           <div class="panel-heading customer-info-view">
                <h3 class="panel-title">客户需求</h3>
                <div class="pull-right" style="margin-top: -26px;">
<!--                     <button class="btn btn-warning btn-edit-customer-info" type="button">修改</button> -->
                </div>
            </div>
            <div class="panel-body customer-info-view">
                <div class="form-group">
                    <label class="control-label col-sm-3">客户需求</label>
                    <div class="col-sm-6 right-text"><?= Html::encode($model->order_customer_need) ?></div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">客户备注</label>
                    <div class="col-sm-6 right-text"><?= Html::encode($model->order_customer_memo) ?></div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">客服备注</label>
                    <div class="col-sm-6 right-text"><?= Html::encode($model->order_cs_memo) ?></div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">系统指派</label>
                    <div class="col-sm-6 right-text"><?= $model->order_flag_sys_assign == 1 ? '是' : '否' ?></div>
                </div>
            </div>
            <div class="panel-heading customer-info-edit">
                <h3 class="panel-title">客户需求</h3>
            </div>
            <div class="panel-body  customer-info-edit">
                <?= $form->field($model, 'order_customer_need')->inline()->checkboxList($model->customerNeeds) ?>
                <?= $form->field($model, 'order_customer_memo')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_cs_memo')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_flag_sys_assign')->inline()->radioList([1=>'是',0=>'否'])->label('系统指派'); ?>
                <div class="form-group">
                    <?= Html::submitButton('提交更改', ['class' =>'btn btn-warning']); ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <button class="btn btn-warning btn-cancel-customer-info" type="button">取消修改</button>
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
    .right-text{
        font-size: 15px;
        margin-top:6px;
    }
');
?>
