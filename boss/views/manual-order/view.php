<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var core\models\Order\Order $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('order', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">
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
            'order_code',
            'order_parent_id',
            'order_is_parent',
            'created_at',
            'updated_at',
            'order_before_status_dict_id',
            'order_before_status_name',
            'order_status_dict_id',
            'order_status_name',
            'order_flag_send',
            'order_flag_urgent',
            'order_flag_exception',
            'order_service_type_id',
            'order_service_type_name',
            'order_src_id',
            'order_src_name',
            'channel_id',
            'order_channel_name',
            'order_channel_order_num',
            'customer_id',
            'order_ip',
            'order_customer_phone',
            'order_booked_begin_time',
            'order_booked_end_time',
            'order_booked_count',
            'address_id',
            'order_address',
            'order_unit_money',
            'order_money',
            'order_booked_worker_id',
            'order_customer_need',
            'order_customer_memo',
            'order_cs_memo',
            'order_pay_type',
            'pay_channel_id',
            'order_pay_channel_name',
            'order_pay_flow_num',
            'order_pay_money',
            'order_use_acc_balance',
            'card_id',
            'order_use_card_money',
            'coupon_id',
            'order_use_coupon_money',
            'promotion_id',
            'order_use_promotion_money',
            'order_lock_status',
            'worker_id',
            'worker_type_id',
            'order_worker_type_name',
            'order_worker_send_type',
            'shop_id',
            'comment_id',
            'order_customer_hidden',
            'order_pop_pay_money',
            'invoice_id',
            'checking_id',
            'admin_id',
            'isdel',
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
