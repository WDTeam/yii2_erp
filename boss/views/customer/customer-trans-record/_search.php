<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\finance\FinanceOrderChannel;
use boss\models\payment\GeneralPay;
use kartik\widgets\Select2;
use yii\helpers\Url;
/**
 * @var yii\web\View $this
 * @var boss\models\CustomerTransRecordSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="customer-trans-record-search  panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 交易记录搜索</h3>
    </div>

    <div class="panel-body row">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>

        <div class="col-md-2">
            <?= $form->field($model, 'customer_id') ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'order_id') ?>
        </div>

        <div class="col-md-2">

            <?php
            $name = FinanceOrderChannel::getOrderChannelByName($model->customer_trans_record_pay_channel);
            echo $form->field($model, 'customer_trans_record_pay_channel')->widget(Select2::classname(),[
                'initValueText' => $name, // set the initial display text
                'attribute'=>'customer_trans_record_pay_channel',
                'model'=>$model,
                'options' => ['placeholder' => '请选择数据来源 ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 0,
                    'ajax' => [
                        'url' => Url::to(['payment/general-pay/order-channel']),
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
            <?php echo $form->field($model, 'customer_trans_record_mode')->widget(Select2::classname(),[
                'initValueText' => '', // set the initial display text
                'attribute'=>'customer_trans_record_mode',
                'model'=>$model,
                'options' => ['placeholder' => '请选择交易方式 ...'],
                'data' => GeneralPay::$PAY_MODE,
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ]);?>
        </div>





        <?php // echo $form->field($model, 'pay_channel_id') ?>

    <?php // echo $form->field($model, 'customer_trans_record_pay_channel') ?>

    <?php // echo $form->field($model, 'customer_trans_record_mode') ?>

    <?php // echo $form->field($model, 'customer_trans_record_mode_name') ?>

    <?php // echo $form->field($model, 'customer_trans_record_promo_code_money') ?>

    <?php // echo $form->field($model, 'customer_trans_record_coupon_money') ?>

    <?php // echo $form->field($model, 'customer_trans_record_cash') ?>

    <?php // echo $form->field($model, 'customer_trans_record_pre_pay') ?>

    <?php // echo $form->field($model, 'customer_trans_record_online_pay') ?>

    <?php // echo $form->field($model, 'customer_trans_record_online_balance_pay') ?>

    <?php // echo $form->field($model, 'customer_trans_record_online_service_card_on') ?>

    <?php // echo $form->field($model, 'customer_trans_record_online_service_card_pay') ?>

    <?php // echo $form->field($model, 'customer_trans_record_compensate_money') ?>

    <?php // echo $form->field($model, 'customer_trans_record_refund_money') ?>

    <?php // echo $form->field($model, 'customer_trans_record_money') ?>

    <?php // echo $form->field($model, 'customer_trans_record_order_total_money') ?>

    <?php // echo $form->field($model, 'customer_trans_record_total_money') ?>

    <?php // echo $form->field($model, 'customer_trans_record_current_balance') ?>

    <?php // echo $form->field($model, 'customer_trans_record_befor_balance') ?>

    <?php // echo $form->field($model, 'customer_trans_record_transaction_id') ?>

    <?php // echo $form->field($model, 'customer_trans_record_remark') ?>

    <?php // echo $form->field($model, 'customer_trans_record_verify') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <div class="col-md-2">
        <label class="control-label" for="generalpaysearch-general_pay_source_name"></label>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
