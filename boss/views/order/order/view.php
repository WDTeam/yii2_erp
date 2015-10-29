<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var dbbase\models\Order $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
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
            'isdel',
            'order_ip',
            'order_service_type_id',
            'order_service_type_name',
            'order_src_id',
            'order_src_name',
            'channel_id',
            'order_channel_name',
            'order_unit_money',
            'order_money',
            'order_booked_count',
            'order_booked_begin_time',
            'order_booked_end_time',
            'address_id',
            'order_address',
            'order_booked_worker_id',
            'checking_id',
            'order_cs_memo',
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
