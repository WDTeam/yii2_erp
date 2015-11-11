<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use dosamigos\datepicker\DatePicker;

/**
 * @var yii\web\View $this
 * @var dbbase\models\Order $model
 */

$this->title = '人工下单';
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-create">
    <div class="order-form">
        <?php if($model->hasErrors()):?>
        <div class="alert alert-danger" role="alert"><?=Html::errorSummary($model); ?></div>
        <?php endif;?>
        <?php $form = ActiveForm::begin(['layout'=>'horizontal','id'=>'order_create_form']); ?>

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">客户信息</h3>
            </div>
            <div class="panel-body">
                <?= $form->field($model, 'order_customer_phone')->textInput(['maxlength' => 11,"autocomplete"=>"off","aria-describedby"=>"sizing-addon1"])->label('客户手机'); ?>
                <div style="display: none;"><?= $form->field($model, 'customer_id')->textInput(['maxlength' => true]) ?></div>
                <div class="form-group field-order-address_id required">
                    <label for="order-address_id" class="control-label col-sm-3">地址信息</label>
                    <div class="col-sm-6">
                        <input type="hidden" value="" name="Order[address_id]">
                        <div id="order-address_id"></div>
                        <?= Html::button('新增地址', ['class' =>  'btn btn-sm btn-warning','id'=>'add_address_btn']); ?>
                        <div class="help-block help-block-error "></div>
                        <div id="address_form" style="display: none;">
                            <div class="col-sm-2" style="padding-left: 0px;">
                                <?= Html::dropDownList('province','',[''=>'请选择省份']+$model->onlineProvinceList,['class'=>'form-control province_form']); ?>
                            </div>
                            <div class="col-sm-2" style="padding-left: 0px;">
                                <?= Html::dropDownList('city','',[''=>'请选择城市'],['class'=>'form-control city_form']); ?>
                            </div>
                            <div class="col-sm-2" style="padding-left: 0px;">
                                <?= Html::dropDownList('county','',[''=>'请选择区县'],['class'=>'form-control  county_form']); ?>
                            </div>
                            <div class="col-sm-4" style="padding-left: 0px;">
                                <?= Html::textInput('detail','',['placeholder'=>'详细地址','class'=>'form-control  detail_form']); ?>
                            </div>
                            <div class="col-sm-3" style="margin-top:0px; display: none;">
                                <?= Html::textInput('nickname','',['placeholder'=>'联系人','class'=>'form-control  nickname_form']); ?>
                            </div>
                            <div class="col-sm-3" style="margin-top:0px;display: none;">
                                <?= Html::textInput('phone','',['placeholder'=>'手机号','class'=>'form-control  phone_form']); ?>
                            </div>
                            <div class="col-sm-2 btn-group" role="group" style="padding-right: 0px;">
                                <button class="btn btn-warning col-sm-6 save_address_btn"  type="button">保存</button>
                                <button class="btn btn-default col-sm-6 cancel_address_btn"  type="button">取消</button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="form-group field-order-order_service_item_id required">
                    <label for="order-order_service_item_id" class="control-label col-sm-3">服务项目</label>
                    <div class="col-sm-6">
                        <input type="hidden" value="" name="Order[order_service_item_id]">
                        <div id="order-order_service_item_id"><p class="form-control-static" style="font-size: 14px;">根据服务地址获取该地址的可服务项目。</p></div>
                        <div class="help-block help-block-error "></div>
                    </div>
                </div>

            </div>
            <div class="panel-heading">
                <h3 class="panel-title">服务信息</h3>
            </div>
            <div class="panel-body">
                <div class="hide">
                <?= $form->field($model, 'order_booked_worker_id')->inline()->radioList(['0'=>'不指定']); ?>
                </div>
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
                <?= $form->field($model, 'orderBookedTimeRange')->inline()
                    ->radioList($model->orderBookedTimeRangeList,['itemOptions'=>['disabled'=>"disabled",'labelOptions' => ['class' => 'radio-inline']]])
                    ->label('服务时间');?>
            </div>
            <div class="panel-heading">
                <h3 class="panel-title">支付信息</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class=" col-sm-3"></label>
                    <h4 class="col-sm-2">
                        单价：<span id="order_unit_money" style="font-size: 25px;color: #00ff00;" >0.00</span>
                    </h4>
                    <h4 class="col-sm-2">
                        总价：<span class="order_money" style="font-size: 25px;color: #ff0000;">0.00</span>
                    </h4>
                    <h4 class="col-sm-5">
                        账户余额：<span id="customer_balance" style="font-size: 25px;">0.00</span>
                    </h4>
                </div>
                <div style="display: none;"><?= $form->field($model, 'order_unit_money')->textInput(['maxlength' => true,'value'=>0]) ?></div>
                <div style="display: none;"><?= $form->field($model, 'order_money')->textInput(['maxlength' => true,'value'=>0]) ?></div>
                <?= $form->field($model, 'order_pay_type')->inline()->radioList(['1'=>'现金支付','2'=>'余额支付','3'=>'第三方预付'])->label('支付方式'); ?>
                <div id="order_pay_type_1" >
                    <div class="form-group">
                        <label class="control-label col-sm-3">需支付</label>
                        <div class="col-sm-2">
                            <span class="order_money" style="font-size: 20px;color: #ff0000;">00.00</span>
                        </div>
                    </div>
                </div>
                <div id="order_pay_type_2" style="display:none;">
                <?= $form->field($model, 'coupon_id')->dropDownList([""=>"请选择优惠券"],['maxlength' => true]) ?>
                <div class="form-group">
                    <label class="control-label col-sm-3">需支付</label>
                    <div class="col-sm-2">
                        <span class="order_pay_money" style="font-size: 20px;color: #ff0000;">00.00</span>
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
                <?= $form->field($model, 'order_customer_need')->inline()->checkboxList($model->customerNeeds) ?>
                <?= $form->field($model, 'order_customer_memo')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_cs_memo')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_flag_sys_assign')->inline()->radioList([1=>'是',0=>'否'])->label('系统指派'); ?>
            </div>
            <div class="panel-footer">
                <div class="form-group">
                    <div class="col-sm-offset-0 col-sm-12">
                        <?= Html::submitButton('创建', ['class' =>'btn btn-warning btn-lg btn-block']); ?>
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
    #order-order_customer_phone{
        font-size:20px;
    }
');
?>
