<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var dbbase\models\PaymentLog $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'General Pay Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-log-view">
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
            '_id',
            'payment_log_price',
            'payment_log_shop_name',
            'payment_log_status_bool',
            'payment_log_eo_order_id',
            'payment_log_transaction_id',

            [
                'attribute' => 'payment_log_status',
                'value' => !empty($model->payment_log_status) ? '成功' : '失败',
            ],
            'pay_channel_id',
            'pay_channel_name',
            'payment_log_json_aggregation',
            [
                'attribute' => 'data',
                'value' => json_encode($model->data),
            ],
            [
                'attribute' => '_SERVER',
                'value' => json_encode($model->_SERVER),
            ],
            'created_at',
            [
                'attribute' => 'create_time',
                'value' => date("Y-m-d H:i:s",$model->create_time),
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
