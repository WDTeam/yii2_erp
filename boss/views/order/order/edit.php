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
                    <div class="col-sm-6 right-text"><?= Html::encode($model->orderExtPay->order_pay_channel_name) ?> <?= Html::encode($model->orderExtPay->order_pay_money) ?></div>
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
                    <div class="col-sm-6 right-text"><?= Html::encode($model->orderExtPay->order_use_coupon_money) ?>元</div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">需支付</label>
                    <div class="col-sm-6 right-text"><?= Html::encode($model->orderExtPay->order_pay_money) ?>元</div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">支付渠道</label>
                    <div class="col-sm-6 right-text"><?= Html::encode($model->orderExtPay->order_pay_channel_name) ?></div>
                </div>
            </div>
           <div class="panel-heading">
                <h3 class="panel-title">用户信息</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label col-sm-3">用户电话</label>
                    <div class="col-sm-6 right-text"><?= Html::encode($model->orderExtCustomer->order_customer_phone) ?></div>
                </div>

                <div class="form-group field-order-address_id required">
                    <label for="order-address_id" class="control-label col-sm-3">地址信息</label>
                    <div class="col-sm-6 right-text">
                        <?= Html::encode($model->order_address);?>
                        <input type="hidden" value="" name="Order[address_id]">
                        <div id="order-address_id"></div>
                        <div class="help-block help-block-error "></div>

                        <div id="address_form" style="display: none;">
                            <div class="col-sm-4" style="padding-left: 0;">
                                <?php //Html::dropDownList('province','',[''=>'请选择省份']+$model->onlineProvinceList,['class'=>'form-control province_form']); ?>
                            </div>
                            <div class="col-sm-3" >
                                <?= Html::dropDownList('city','',[''=>'请选择城市'],['class'=>'form-control city_form']); ?>
                            </div>
                            <div class="col-sm-4">
                                <?= Html::dropDownList('county','',[''=>'请选择区县'],['class'=>'form-control  county_form']); ?>
                            </div>
                            <button class="btn btn-sm btn-warning col-sm-1 cancel_address_btn" style="margin-top:10px;" type="button">取消</button>
                            <div class="col-sm-5" style="padding-left: 0; margin-top:10px;">
                                <?= Html::textInput('detail','',['placeholder'=>'详细地址','class'=>'form-control  detail_form']); ?>
                            </div>
                            <div class="col-sm-3" style="margin-top:10px;">
                                <?= Html::textInput('nickname','',['placeholder'=>'联系人','class'=>'form-control  nickname_form']); ?>
                            </div>
                            <div class="col-sm-3" style="margin-top:10px;">
                                <?= Html::textInput('phone','',['placeholder'=>'手机号','class'=>'form-control  phone_form']); ?>
                            </div>
                            <button class="btn btn-sm btn-warning col-sm-1 save_address_btn" style="margin-top:10px;" type="button">保存</button>
                        </div>
                    </div>
                </div>
                <!--div>
                <?= Html::button('修改', ['class' =>  'btn btn-warning pull-right address-edit-btn','id'=>'add_address_btn']); ?>
                <?= Html::button('保存', ['class' =>  'btn btn-warning pull-right address-save-btn','style'=>'display:none']); ?>
                </div-->
                <div class="form-group">
                    <label class="control-label col-sm-3">服务类型</label>
                    <div class="col-sm-6 right-text"><?= Html::encode($model->order_service_type_name) ?></div>
                </div>
            </div>
           <div class="panel-heading service-info-view">
                <h3 class="panel-title">服务信息</h3>
                <!--div class="pull-right" style="margin-top: -26px;">
                     <button class="btn btn-warning btn-edit-service-info" type="button">修改</button>
                </div-->
            </div>
            <div class="panel-body service-info-view">
                <div class="form-group">
                    <label class="control-label col-sm-3">指定阿姨</label>
                    <div class="col-sm-6 right-text"></div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">服务时间</label>
                    <div class="col-sm-6 right-text service_time_html"><?= $model->getOrderBookedDate().' '.$model->getOrderBookedTimeArrange() ?></div>
                </div>
            </div>
            <div class="panel-heading service-info-edit">
                <h3 class="panel-title">服务信息</h3>
            </div>
            <div class="panel-body service-info-edit">

                <?php
                    $model->orderBookedDate = date("Y-m-d",$model->order_booked_begin_time);
                    echo $form->field($model, 'orderBookedDate')->label('服务日期')->widget(
                        DatePicker::className(), [
                        'inline' => true,
                        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px;font-size:14px;">{input}</div>',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                            'startDate' => date('Y-m-d'),
                        ],
                        'language'=>'zh-CN'
                    ]);
                ?>
                <div class="form-group field-order-order_booked_count">
                    <label class="control-label col-sm-3" for="order-order_booked_count">服务时长</label>
                    <div class="col-sm-6">
                        <?=$model->orderBookedCountList[$model->order_booked_count]; ?>
                        <div style="display: none" id="order-order_booked_count">
                            <input type="radio" value="<?=$model->order_booked_count;?>" checked="checked" name="Order[order_booked_count]">
                        </div>
                    </div>

                </div>
                <?php
                    //如果已经指派阿姨,获取指定阿姨的排班表,否则获取全部阿姨的排班表
                    if(!empty($model->worker_id)){

                    }else{
                        $model->orderBookedTimeRange = date('G:i',$model->order_booked_begin_time).'-'.date('G:i',$model->order_booked_end_time);
                        echo $form->field($model, 'orderBookedTimeRange')->inline()
                            ->radioList($model->thisOrderBookedTimeRangeList)
                            ->label('服务时间');
                    }
                ?>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-12">
                        <button class="btn btn-warning order_service_info_save" type="button">保存更改</button>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <button class="btn btn-warning btn-cancel-service-info" type="button">取消更改</button>
                    </div>
                </div>
            </div>
           <div class="panel-heading customer-info-view">
                <h3 class="panel-title">客户需求</h3>
                <div class="pull-right" style="margin-top: -26px;">
                     <button class="btn btn-warning btn-edit-customer-info" type="button">修改</button>
                </div>
            </div>
            <div class="panel-body customer-info-view">
                <div class="form-group">
                    <label class="control-label col-sm-3">客户需求</label>
                    <div class="col-sm-6 right-text order_customer_need"><?= Html::encode($model->orderExtCustomer->order_customer_need) ?></div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">客户备注</label>
                    <div class="col-sm-6 right-text order_customer_memo"><?= Html::encode($model->orderExtCustomer->order_customer_memo) ?></div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">客服备注</label>
                    <div class="col-sm-6 right-text order_cs_memo"><?= Html::encode($model->order_cs_memo) ?></div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">系统指派</label>
                    <div class="col-sm-6 right-text"><?= $model->orderExtFlag->order_flag_sys_assign == 1 ? '是' : '否' ?></div>
                </div>
            </div>
            <div class="panel-heading customer-info-edit">
                <h3 class="panel-title">客户需求</h3>
            </div>
            <div class="panel-body  customer-info-edit">
                <?php
                    $model->order_customer_need = explode(',',$model->orderExtCustomer->order_customer_need);
                    echo $form->field($model, 'order_customer_need')->inline()->checkboxList($model->customerNeeds)
                ?>
                <?= $form->field($model, 'order_customer_memo')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_cs_memo')->textInput(['maxlength' => true]) ?>

                <div class="form-group">
                    <label class="control-label col-sm-3">系统指派</label>
                    <div class="col-sm-6 right-text"><?= $model->orderExtFlag->order_flag_sys_assign == 1 ? '是' : '否' ?></div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-12">
                        <button class="btn btn-warning order_customer_need_save" type="button">保存更改</button>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <button class="btn btn-warning btn-cancel-customer-info" type="button">取消更改</button>
                    </div>
                </div>
            </div>                       
        </div>
            <?= $form->field($model, 'id')->hiddenInput()->label('') ?>
            <?= $form->field($model, 'customer_id')->hiddenInput()->label('') ?>

        <?php ActiveForm::end(); ?>

    </div>

</div>
<?php

$this->registerJsFile('/js/order.js',['depends'=>[ 'yii\web\YiiAsset','yii\bootstrap\BootstrapAsset']]);
$this->registerJs("
    district_id = {$model->district_id};
");
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
