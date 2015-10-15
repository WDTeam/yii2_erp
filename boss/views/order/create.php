<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use dosamigos\datepicker\DatePicker;

/**
 * @var yii\web\View $this
 * @var common\models\Order $model
 */

$this->title = '创建订单';
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
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
                <h3 class="panel-title">客户信息</h3>
            </div>
            <div class="panel-body">
                <?= $form->field($model, 'order_customer_phone')->textInput(['maxlength' => 11])->label('客户手机'); ?>
                <div style="display: none;"><?= $form->field($model, 'customer_id')->textInput(['maxlength' => true]) ?></div>
                <div id="address_div"><?= $form->field($model, 'address_id')->radioList([''=>'请先输入手机号获取地址信息'])->label('地址信息') ?></div>
                <div style="display: none;"><?= $form->field($model, 'order_address')->textInput(['maxlength' => true]) ?></div>
                <?= $form->field($model, 'order_service_type_id')->inline()->radioList([''=>'选择地址获取商品'])->label('选择商品') ?>
            </div>
            <div class="panel-heading">
                <h3 class="panel-title">服务信息</h3>
            </div>
            <div class="panel-body">
                <?= $form->field($model, 'order_booked_worker_id')->inline()->radioList(['0'=>'不指定']); ?>
                <?= $form->field($model, 'orderBookedDate')->label('服务日期')->widget(
                    DatePicker::className(), [
                    'inline' => true,
                    'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
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
                <?= $form->field($model, 'order_pay_type')->inline()->radioList(['1'=>'现金支付','2'=>'线上支付','3'=>'第三方预付'])->label('支付方式'); ?>
                <div id="order_pay_type_1" >
                    <div class="form-group">
                        <label class="control-label col-sm-3">需支付</label>
                        <div class="col-sm-2">
                            <span class="order_money" style="font-size: 20px;color: #ff0000;">50.00</span>
                        </div>
                    </div>
                </div>
                <div id="order_pay_type_2" style="display:none;">
                <?= $form->field($model, 'coupon_id')->dropDownList([],['maxlength' => true]) ?>
                <?= $form->field($model, 'card_id')->dropDownList([],['maxlength' => true]) ?>
                <?= $form->field($model, 'order_use_acc_balance')->textInput(['maxlength' => true]) ?>
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
                <?= $form->field($model, 'order_customer_need')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_customer_memo')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_cs_memo')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="panel-footer">
                <div class="form-group">
                    <div class="col-sm-offset-0 col-sm-12">
                        <?= Html::submitButton('创建', ['class' => $model->isNewRecord ? 'btn btn-success btn-lg btn-block' : 'btn btn-primary btn-lg btn-block']); ?>
                    </div>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
<?php
$this->registerJsFile('/js/order.js',['depends'=>[ 'yii\web\YiiAsset','yii\bootstrap\BootstrapAsset']]);
$this->registerCss('
 #order-order_booked_time_range .radio-inline ,#order-order_booked_time_range .checkbox-inline {
        margin-left: 0px;
        margin-right: 10px;
        margin-top: 0;
    }
');
?>