<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var dbbase\models\CustomerTransRecord $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customer Trans Records'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-customer-trans-record-view">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>


    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'id',
            'customer_id',
            'order_id',
            'order_code',
            'order_batch_code',
            'order_channel_id',
            'payment_customer_trans_record_order_channel',
            'pay_channel_id',
            'payment_customer_trans_record_pay_channel',
            'payment_customer_trans_record_mode',
            'payment_customer_trans_record_mode_name',
            'payment_customer_trans_record_coupon_money',
            'payment_customer_trans_record_cash',
            'payment_customer_trans_record_pre_pay',
            'payment_customer_trans_record_online_pay',
            'payment_customer_trans_record_online_balance_pay',
            'payment_customer_trans_record_service_card_on',
            'payment_customer_trans_record_service_card_pay',
            'payment_customer_trans_record_service_card_current_balance',
            'payment_customer_trans_record_service_card_befor_balance',
            'payment_customer_trans_record_compensate_money',
            'payment_customer_trans_record_refund_money',
            'payment_customer_trans_record_order_total_money',
            'payment_customer_trans_record_total_money',
            'payment_customer_trans_record_current_balance',
            'payment_customer_trans_record_befor_balance',
            'payment_customer_trans_record_transaction_id',
            'payment_customer_trans_record_remark',
            'payment_customer_trans_record_verify',
            [
                'attribute' => 'created_at',
                'value' => date("Y-m-d H:i:s",$model->created_at),
            ],
            [
                'attribute' => 'updated_at',
                'value' => date("Y-m-d H:i:s",$model->updated_at),
            ],
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->id],
        'data'=>[
        'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
        'method'=>'post',
        ],
        ],
        'enableEditMode'=>false,
    ]) ?>

</div>
