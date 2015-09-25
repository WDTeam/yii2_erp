<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

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
                <?= $form->field($model, 'customer_id')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_customer_phone')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'address_id')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_address')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_service_type_id')->textInput() ?>
                <?= $form->field($model, 'order_service_type_name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="panel-heading">
                <h3 class="panel-title">服务信息</h3>
            </div>
            <div class="panel-body">
                <?= $form->field($model, 'order_booked_worker_id')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_booked_begin_time')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_booked_end_time')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_booked_count')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'shop_id')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_worker_type_name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="panel-heading">
                <h3 class="panel-title">支付信息</h3>
            </div>
            <div class="panel-body">
                <?= $form->field($model, 'order_unit_money')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'order_money')->textInput(['maxlength' => true]) ?>
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
