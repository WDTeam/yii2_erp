<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var dbbase\models\CustomerTransRecordLog $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customer Trans Record Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-trans-record-log-view">
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
            'order_channel_id',
            'customer_trans_record_order_channel',
            'pay_channel_id',
            'customer_trans_record_pay_channel',
            'customer_trans_record_mode',
            'customer_trans_record_mode_name',
            'customer_trans_record_promo_code_money',
            'customer_trans_record_coupon_money',
            'customer_trans_record_cash',
            'customer_trans_record_pre_pay',
            'customer_trans_record_online_pay',
            'customer_trans_record_online_balance_pay',
            'customer_trans_record_online_service_card_on',
            'customer_trans_record_online_service_card_pay',
            'customer_trans_record_online_service_card_current_balance',
            'customer_trans_record_online_service_card_befor_balance',
            'customer_trans_record_compensate_money',
            'customer_trans_record_refund_money',
            'customer_trans_record_money',
            'customer_trans_record_order_total_money',
            'customer_trans_record_total_money',
            'customer_trans_record_current_balance',
            'customer_trans_record_befor_balance',
            'customer_trans_record_transaction_id',
            'customer_trans_record_remark',
            'customer_trans_record_verify',
            'created_at',
            'updated_at',
            'is_del',
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->id],
        'data'=>[
        'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
        'method'=>'post',
        ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
