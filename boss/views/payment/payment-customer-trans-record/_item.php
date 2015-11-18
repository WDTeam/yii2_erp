<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use core\models\order\OrderStatusDict;
use boss\models\worker\Worker;
use boss\models\order\Order;

?>
<style>
    .pay_record{
        line-height: 27px;
    }
    .table{
        width:98%;
    }
    .list-view{
        padding:18px;
    }
    .first{
        background: #F3F3F3;
    }
    table tr.first td{
        line-height: 30px;
        font-size: 14px;
        color: #45464B;
        font-family: 微软雅黑;
    }
    table tr td{
        font-size:13px;
        font-family: 微软雅黑;
    }
    .last{
        float: right;
    }

</style>
<div class="m_tab">
    <table class="table table-bordered">
        <tr class="first">
             <td colspan="5">
                用户手机号码：<?= Html::encode($model->customer_phone) ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                E家洁订单编号：<?= Html::encode($model->order_code) ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                E家洁周期订单号：<?= Html::encode($model->order_batch_code) ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                交易方式：<?= Html::encode($model->payment_customer_trans_record_mode_name) ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                订单渠道：<?= Html::encode($model->payment_customer_trans_record_order_channel) ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                用户累计交易额：<span><?= Html::encode($model->payment_customer_trans_record_total_money) ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="last" href="<?= Yii::$app->urlManager->createUrl(['payment/payment-customer-trans-record/view', 'id' => $model->id])?>">查看详情</a>
             </td>
        </tr>
        <tr>
            <td width="20%">
                <div class="pay_record"><br/><div>
                <div class="pay_record">优惠券ID：<span><?= Html::encode($model->payment_customer_trans_record_coupon_id) ?></span></div>
                <div class="pay_record">优惠券编码：<span><?= Html::encode($model->payment_customer_trans_record_coupon_code) ?></span></div>
                <div class="pay_record">优惠券金额：<span><?= Html::encode($model->payment_customer_trans_record_coupon_money) ?></span></div>
                <div class="pay_record">优惠券流水号：<span><?= Html::encode($model->payment_customer_trans_record_coupon_transaction_id) ?></span></div>
            </td>
            <td width="20%">
                <div class="pay_record"><br/>
                <div class="pay_record">支付前用户余额：<span><?= Html::encode($model->payment_customer_trans_record_befor_balance) ?></span></div>
                <div class="pay_record">支付后用户余额：<span><?= Html::encode($model->payment_customer_trans_record_current_balance) ?></span></div>
                <div class="pay_record">用户余额支付：<span><?= Html::encode($model->payment_customer_trans_record_online_balance_pay) ?></span></div>
                <div class="pay_record">余额流水号：<span><?= Html::encode($model->payment_customer_trans_record_balance_transaction_id) ?></span></div>
            </td>
            <td width="20%">
                <div class="pay_record">服务卡卡号：<span><?= Html::encode($model->payment_customer_trans_record_service_card_on) ?></span></div>
                <div class="pay_record">支付前服务卡余额：<span><?= Html::encode($model->payment_customer_trans_record_service_card_current_balance) ?></span></div>
                <div class="pay_record">支付后服务卡余额：<span><?= Html::encode($model->payment_customer_trans_record_service_card_befor_balance) ?></span></div>
                <div class="pay_record">服务卡支付：<span><?= Html::encode($model->payment_customer_trans_record_service_card_pay) ?></span></div>
                <div class="pay_record">服务卡流水号：<span><?= Html::encode($model->payment_customer_trans_record_service_card_transaction_id) ?></span></div>
            </td>
            <td width="20%">
                <div class="pay_record"><br/>
                <div class="pay_record">支付渠道：<span><?= Html::encode($model->payment_customer_trans_record_pay_channel) ?></span></div>
                <div class="pay_record">商户订单号：<span><?= Html::encode($model->payment_customer_trans_record_eo_order_id) ?></span></div>
                <div class="pay_record">在线支付：<span><?= Html::encode($model->payment_customer_trans_record_online_pay) ?></span></div>
                <div class="pay_record">交易流水号：<span><?= Html::encode($model->payment_customer_trans_record_transaction_id) ?></span></div>
            </td>
            <td width="20%">
                <div class="pay_record">现金支付：<span><?= Html::encode($model->payment_customer_trans_record_cash) ?></span></div>
                <div class="pay_record">预付费金额(第三方)：<span><?= Html::encode($model->payment_customer_trans_record_pre_pay) ?></span></div>
                <div class="pay_record">补偿金额：<span><?= Html::encode($model->payment_customer_trans_record_compensate_money) ?></span></div>
                <div class="pay_record">订单总金额：<span><?= Html::encode($model->payment_customer_trans_record_order_total_money) ?></span></div>
                <div class="pay_record">退款金额：<span><?= Html::encode($model->payment_customer_trans_record_refund_money) ?></span></div>


            </td>
        </tr>
    </table>
</div>