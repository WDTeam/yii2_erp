<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\base\Widget;
use kartik\widgets\Affix;
/**
 * @var yii\web\View $this
 * @var common\models\pay\GeneralPaySearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="general-pay-search panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 支付搜索</h3>
    </div>
    <div class="panel-body row">

        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>

        <div class="col-md-2">
            <?= $form->field($model, 'general_pay_transaction_id') ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'customer_id') ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'order_id') ?>
        </div>

        <div class="col-md-2">

            <?php
                $name = \common\models\FinanceOrderChannel::getOrderChannelByName($model->general_pay_source);
                echo $form->field($model, 'general_pay_source')->widget(Select2::classname(),[
                'initValueText' => $name, // set the initial display text
                'attribute'=>'general_pay_source',
                'model'=>$model,
                'options' => ['placeholder' => '请选择数据来源 ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 0,
                    'ajax' => [
                        'url' => Url::to(['general-pay/order-channel']),
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
            <?php echo $form->field($model, 'general_pay_mode')->widget(Select2::classname(),[
                'initValueText' => '', // set the initial display text
                'attribute'=>'general_pay_mode',
                'model'=>$model,
                'options' => ['placeholder' => '请选择交易方式 ...'],
                'data' => \boss\models\pay\GeneralPay::$PAY_MODE,
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ]);?>


        </div>


        <?php // echo $form->field($model, 'general_pay_source') ?>

        <?php // echo $form->field($model, 'general_pay_source_name') ?>

        <?php // echo $form->field($model, 'general_pay_mode') ?>

        <?php // echo $form->field($model, 'general_pay_status') ?>

        <?php // echo $form->field($model, 'general_pay_transaction_id') ?>

        <?php // echo $form->field($model, 'general_pay_eo_order_id') ?>

        <?php // echo $form->field($model, 'general_pay_memo') ?>

        <?php // echo $form->field($model, 'general_pay_is_coupon') ?>

        <?php // echo $form->field($model, 'admin_id') ?>

        <?php // echo $form->field($model, 'general_pay_admin_name') ?>

        <?php // echo $form->field($model, 'worker_id') ?>

        <?php // echo $form->field($model, 'handle_admin_id') ?>

        <?php // echo $form->field($model, 'general_pay_handle_admin_name') ?>

        <?php // echo $form->field($model, 'general_pay_verify') ?>

        <?php // echo $form->field($model, 'is_reconciliation') ?>

        <?php // echo $form->field($model, 'created_at') ?>

        <?php // echo $form->field($model, 'updated_at') ?>

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
