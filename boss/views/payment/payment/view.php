<?php

use kartik\detail\DetailView;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\Payment $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '支付列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="general-pay-view">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>


    <?= DetailView::widget([
        'model' => $model,
        'condensed' => false,
        'hover' => true,
        'mode' => Yii::$app->request->get('edit') == 't' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
        'panel' => [
            'heading' => $this->title,
            'type' => DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'id',
            'customer_id',
            'order_id',
            'payment_money',
            'payment_actual_money',
            'payment_source',
            'payment_channel_id',
            'payment_channel_name',
            'payment_mode',
            [
                'attribute' => 'payment_status',
                'value' => $model::$PAY_MODE[$model->payment_mode],
            ],

            [
                'attribute' => 'payment_status',
                'value' => $model::$PAY_STATUS[$model->payment_status],
            ],
            'payment_transaction_id',
            'payment_eo_order_id',
            'payment_memo',
            'payment_type',
            'admin_id',
            'payment_admin_name',
            'worker_id',
            'handle_admin_id',
            'payment_handle_admin_name',
            'payment_verify',
            [
                'attribute' => 'created_at',
                'value' => date("Y-m-d H:i:s",$model->created_at),
            ],
            [
                'attribute' => 'updated_at',
                'value' => date("Y-m-d H:i:s",$model->updated_at),
            ],
        ],
        'deleteOptions' => [
            'url' => ['delete', 'id' => $model->id],
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ],
        'enableEditMode' => false,
    ]) ?>

</div>
