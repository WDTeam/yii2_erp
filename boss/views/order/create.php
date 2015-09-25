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
        <?php $form = ActiveForm::begin(['layout'=>'horizontal']); ?>

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">用户信息</h3>
            </div>
            <div class="panel-body">
                <?= $form->field($model, 'order_customer_phone')->textInput(['maxlength' => 11]) ?>
                <div style="display: block;"><?= $form->field($model, 'customer_id')->textInput(['maxlength' => true]) ?></div>
                <div id="address_div" style="display: none;"><?= $form->field($model, 'address_id')->radioList([1=>'ddddd',2=>'ggggg'])->label('地址') ?></div>
                <div style="display: block;"><?= $form->field($model, 'order_address')->textInput(['maxlength' => true]) ?></div>
                <?= $form->field($model, 'order_service_type_id')->dropDownList([1=>'家庭保洁',2=>'新居开荒']) ?>
                <?= $form->field($model, 'order_service_type_name')->textInput(['maxlength' => true,'value'=>'家庭保洁']) ?>
            </div>
            <div class="panel-heading">
                <h3 class="panel-title">服务信息</h3>
            </div>
            <div class="panel-body">
                <div class="form-group field-order-order_customer_phone required has-success">
                    <label for="order-order_customer_phone" class="control-label col-sm-3">阿姨手机号</label>
                    <div class="col-sm-6">
                        <input type="text" maxlength="11"  class="form-control" id="order-order_booked_worker_phone">
                        <div class="help-block help-block-error "></div>
                    </div>

                </div>
                <?php $model->order_booked_worker_id=0; //默认值
                echo $form->field($model, 'order_booked_worker_id')->radioList(['0'=>'不指定']) ?>
                <?= $form->field($model, 'order_booked_count')->dropDownList(["120"=>"两小时","150"=>"两个半小时","180"=>"三小时","210"=>"三个半小时","240"=>"四小时","270"=>"四个半小时","300"=>"五小时","330"=>"五个半小时","360"=>"六小时"])->label('预约服务时长') ?>
                <?= $form->field($model, 'order_booked_begin_time')->widget(
                    DatePicker::className(), [
                    'inline' => true,
                    'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]);?>
                <?= $form->field($model, 'order_booked_begin_time')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_booked_end_time')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'shop_id')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_worker_type_name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="panel-heading">
                <h3 class="panel-title">支付信息</h3>
            </div>
            <div class="panel-body">
                <?= $form->field($model, 'order_unit_money')->textInput(['maxlength' => true,'value'=>25]) ?>
                <?= $form->field($model, 'order_money')->textInput(['maxlength' => true,'value'=>50]) ?>
                <?= $form->field($model, 'coupon_id')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_use_coupon_money')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_pay_type')->textInput() ?>
                <?= $form->field($model, 'pay_channel_id')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_pay_money')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_use_acc_balance')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'card_id')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_use_card_money')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'promotion_id')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_use_promotion_money')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_pay_channel_name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_pay_flow_num')->textInput(['maxlength' => true]) ?>
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