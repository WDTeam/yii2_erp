<?php

use boss\models\payment\Payment;

use kartik\widgets\Select2;
use kartik\widgets\Affix;

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\base\Widget;

/**
 * @var yii\web\View $this
 * @var dbbase\models\payment\PaymentSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="payment-search panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 支付搜索</h3>
    </div>
    <div class="panel-body row">

        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>

        <div class="col-md-2">
            <?= $form->field($model, 'payment_transaction_id') ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'customer_id') ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'order_id') ?>
        </div>

        <div class="col-md-2">

            <?php
                $name = $model->getOrderChannelName($model->payment_source);
                echo $form->field($model, 'payment_source')->widget(Select2::classname(),[
                'initValueText' => $name, // set the initial display text
                'attribute'=>'payment_source',
                'model'=>$model,
                'options' => ['placeholder' => '请选择数据来源 ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 0,
                    'ajax' => [
                        'url' => Url::to(['order-channel']),
                        'dataType' => 'json',
                        //'data' => new JsExpression('function(params) { return console.log(params);{q:params.term}; }')
                    ],
                    //'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    //'templateResult' => new JsExpression('function(model) { return model.finance_order_channel_name; }'),
                    //'templateSelection' => new JsExpression('function (model) { return model.finance_order_channel_name; }')
                ]
            ]);?>

        </div>

        <div class="col-md-2">
            <?php echo $form->field($model, 'payment_mode')->widget(Select2::classname(),[
                'initValueText' => '', // set the initial display text
                'attribute'=>'payment_mode',
                'model'=>$model,
                'options' => ['placeholder' => '请选择交易方式 ...'],
                'data' => Payment::$PAY_MODE,
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ]);?>
        </div>


        <?php // echo $form->field($model, 'payment_source') ?>

        <?php // echo $form->field($model, 'payment_source_name') ?>

        <?php // echo $form->field($model, 'payment_mode') ?>

        <?php // echo $form->field($model, 'payment_status') ?>

        <?php // echo $form->field($model, 'payment_transaction_id') ?>

        <?php // echo $form->field($model, 'payment_eo_order_id') ?>

        <?php // echo $form->field($model, 'payment_memo') ?>

        <?php // echo $form->field($model, 'payment_is_coupon') ?>

        <?php // echo $form->field($model, 'admin_id') ?>

        <?php // echo $form->field($model, 'payment_admin_name') ?>

        <?php // echo $form->field($model, 'worker_id') ?>

        <?php // echo $form->field($model, 'handle_admin_id') ?>

        <?php // echo $form->field($model, 'payment_handle_admin_name') ?>

        <?php // echo $form->field($model, 'payment_verify') ?>

        <?php // echo $form->field($model, 'is_reconciliation') ?>

        <?php // echo $form->field($model, 'created_at') ?>

        <?php // echo $form->field($model, 'updated_at') ?>

        <div class="col-md-2">
            <label class="control-label" for="Paymentsearch-payment_source_name"></label>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
                    <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
                </div>
        </div>


        <?php ActiveForm::end(); ?>
    </div>
</div>
