<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

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

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
