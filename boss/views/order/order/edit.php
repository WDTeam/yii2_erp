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
                        <input type="hidden" id="address_id" value="<?= Html::encode($model->address_id);?>">
                        <span id="address_static_label"><?= Html::encode($model->order_address);?></span>
                        <div id="address_form" style="display: none;"></div>
                    </div>
                    <div class="col-sm-3">
                        <button class="btn btn-warning btn-xs btn-edit-address-info" type="button">修改地址</button>
                    </div>

                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">服务类型</label>
                    <div class="col-sm-6 right-text"><?= Html::encode($model->order_service_type_name) ?></div>
                </div>
            </div>

            <!-- 服务信息START -->
           <div class="panel-heading service-info-view">
                <h3 class="panel-title">服务信息</h3>
            </div>
            <div class="panel-body service-info-view">
                <div class="form-group">
                    <label class="control-label col-sm-3">已派阿姨</label>
                    <div class="col-sm-6 right-text"><?=$model->orderExtWorker->order_worker_name;?></div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">服务时间</label>
                    <div class="col-sm-6 right-text service_time_html">
                        <?= $model->getOrderBookedDate().' '.$model->getOrderBookedTimeArrange() ?>
                    </div>
                    <div class="col-sm-3">
                        <button class="btn btn-warning btn-xs btn-edit-service-info" type="button">修改时间</button>
                    </div>
                </div>
            </div>
            <!-- 服务信息修改END -->




            <!-- 服务信息修改START -->

            <div class="panel-heading service-info-edit">
                <h3 class="panel-title">服务信息</h3>
            </div>
            <div class="panel-body service-info-edit">

                <div class="form-group field-order-order_booked_count">
                    <?php
                    //默认不指派阿姨
                    $workerList = [0=>'无'];
                    //判断是否已经指定阿姨
                    if( !empty($model->orderExtWorker->worker_id) ) {
                        $workerList[$model->orderExtWorker->worker_id] = $model->orderExtWorker->order_worker_name;
                    }
                    //显示指定阿姨
                    echo $form->field($model->orderExtWorker, 'worker_id')->inline()
                        ->radioList($workerList)
                        ->label('指派阿姨');
                    ?>
                </div>

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
                    $model->orderBookedTimeRange = date('G:i',$model->order_booked_begin_time).'-'.date('G:i',$model->order_booked_end_time);
                    echo $form->field($model, 'orderBookedTimeRange')->inline()
                        ->radioList($model->thisOrderBookedTimeRangeList)
                        ->label('服务时间');

                    if(!empty($model->orderExtWorker->worker_id)){

                    }else{

                    }
                ?>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-12">
                        <button class="btn btn-warning order_service_info_save" type="button">保存更改</button>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <button class="btn btn-default btn-cancel-service-info" type="button">取消更改</button>
                    </div>
                </div>
            </div>
            <!-- 服务信息修改END -->


           <!-- 客户需求START -->
           <div class="panel-heading customer-info-view">
                <h3 class="panel-title">客户需求</h3>
            </div>
            <div class="panel-body customer-info-view">
                <div class="form-group">
                    <label class="control-label col-sm-3">客户需求</label>
                    <div class="col-sm-6 right-text order_customer_need"><?= Html::encode($model->orderExtCustomer->order_customer_need) ?></div>
                    <div class="col-sm-3"><button class="btn btn-warning btn-xs btn-edit-customer-info" type="button">修改客户需求</button></div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">客户备注</label>
                    <div class="col-sm-6 right-text order_customer_memo"><?= Html::encode($model->orderExtCustomer->order_customer_memo) ?></div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">客服备注</label>
                    <div class="col-sm-6 right-text order_cs_memo"><?= Html::encode($model->order_cs_memo) ?></div>
                </div>
            </div>
            <!-- 客户需求END -->


            <!-- 客户需求修改START -->
            <div class="panel-heading customer-info-edit">
                <h3 class="panel-title">客户需求</h3>
            </div>
            <div class="panel-body  customer-info-edit">
                <?php
                    $model->order_customer_need = explode(',',$model->orderExtCustomer->order_customer_need);
                    echo $form->field($model, 'order_customer_need')->inline()->checkboxList($model->customerNeeds)
                ?>
                <?php
                    $model->order_customer_memo = $model->orderExtCustomer->order_customer_memo;
                    echo $form->field($model, 'order_customer_memo')->textInput(['maxlength' => true]);
                ?>
                <?= $form->field($model, 'order_cs_memo')->textInput(['maxlength' => true]) ?>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-12">
                        <button class="btn btn-warning order_customer_need_save" type="button">保存更改</button>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <button class="btn btn-warning btn-cancel-customer-info" type="button">取消更改</button>
                    </div>
                </div>
            </div>
            <!-- 客户需求修改END -->
        </div>
            <?= $form->field($model, 'id')->hiddenInput()->label('') ?>
            <?= $form->field($model, 'order_code')->hiddenInput()->label('') ?>
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
