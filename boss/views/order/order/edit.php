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
