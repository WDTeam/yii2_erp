<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\DateTimePicker;

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
        <?=Html::errorSummary($model); ?>
        <?php $form = ActiveForm::begin(['layout'=>'horizontal']); ?>

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">顾客信息</h3>
            </div>
            <div class="panel-body">
                <?= $form->field($model, 'order_customer_phone')->textInput(['maxlength' => 11])->label('顾客手机'); ?>
                <div style="display: none;"><?= $form->field($model, 'customer_id')->textInput(['maxlength' => true]) ?></div>
                <div id="address_div" style="display: none;"><?= $form->field($model, 'address_id')->radioList([1=>'ddddd',2=>'ggggg'])->label('地址信息') ?></div>
                <div style="display: none;"><?= $form->field($model, 'order_address')->textInput(['maxlength' => true]) ?></div>
                <?= $form->field($model, 'order_service_type_id')->dropDownList($service_list)->label('服务类别') ?>
                <div style="display: none;"><?= $form->field($model, 'order_service_type_name')->textInput(['maxlength' => true,'value'=>$service_list[1]]) ?></div>
            </div>
            <div class="panel-heading">
                <h3 class="panel-title">服务信息</h3>
            </div>
            <div class="panel-body">
                <div class="form-group field-order-order_customer_phone required has-success">
                    <label for="order-order_customer_phone" class="control-label col-sm-3">阿姨手机</label>
                    <div class="col-sm-6">
                        <input type="text" maxlength="11"  class="form-control" id="order-order_booked_worker_phone">
                        <div class="help-block help-block-error "></div>
                    </div>

                </div>
                <?php $model->order_booked_worker_id=0; //默认值
                echo $form->field($model, 'order_booked_worker_id')->radioList(['0'=>'不指定']) ?>

                <?= $form->field($model, 'order_booked_count')->dropDownList(["120"=>"两小时","150"=>"两个半小时","180"=>"三小时","210"=>"三个半小时","240"=>"四小时","270"=>"四个半小时","300"=>"五小时","330"=>"五个半小时","360"=>"六小时"])->label('预约服务时长') ?>
                <?= $form->field($model, 'order_booked_begin_time')->widget(
                    DateTimePicker::className(), [
                    'type'=>DateTimePicker::TYPE_COMPONENT_INPUT_CENTER,
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-mm-dd hh:ii:00',
                        'startDate' => date('Y-m-d'),
                        'hoursDisabled' => '0,1,2,3,4,5,6,7,21,22,23',
                        'minuteStep' => 30,
                    ],
                    'options'=>[
                        'value'=>date('Y-m-d H:00:00',strtotime('+1 hours')),
//                        'disabled' => true,
                    ]
                ]);?>
                <?= $form->field($model, 'order_booked_end_time')->widget(
                    DateTimePicker::className(), [
                    'type'=>DateTimePicker::TYPE_COMPONENT_INPUT_CENTER,
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-mm-dd hh:ii:00',
                        'startDate' => date('Y-m-d'),
                        'hoursDisabled' => '0,1,2,3,4,5,6,7,21,22,23',
                        'minuteStep' => 30,
                    ],
                    'options'=>[
                         'value'=>date('Y-m-d H:00:00',strtotime('+3 hours')),
                    ],
//                    'disabled' => true,
                ]);?>
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
                        总价：<span id="order_money" style="font-size: 25px;color: #ff0000;">50.00</span>
                    </h4>
                    <h4 class="col-sm-2">
                        账户余额：<span id="customer_balance" style="font-size: 25px;">0.00</span>
                    </h4>
                </div>
                <?= $form->field($model, 'order_pay_type')->dropDownList([1=>'现金支付','2'=>'余额支付'])->label('支付方式') ?>
                <div style="display: none;"><?= $form->field($model, 'order_unit_money')->textInput(['maxlength' => true,'value'=>25]) ?></div>
                <div style="display: none;"><?= $form->field($model, 'order_money')->textInput(['maxlength' => true,'value'=>50]) ?></div>
                <div id="is_acc_balance_pay" style="display:none;">
                <?= $form->field($model, 'coupon_id')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_use_coupon_money')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'card_id')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_use_card_money')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'promotion_id')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_use_promotion_money')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_use_acc_balance')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="panel-heading">
                <h3 class="panel-title">用户需求</h3>
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
?>