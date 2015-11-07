<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var dbbase\models\Order $model
 */

$this->title = "订单号：".$model->order_code;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">

    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
                'heading'=> '<button class="btn btn-warning" onclick="location.href=\'/order/order/create\'" >创建新订单</button>',
                'type'=>DetailView::TYPE_INFO,
            ],
            'attributes' => [
                ['attribute'=>'created_at','value'=>date('Y-m-d H:i:s',$model->created_at)],
                'order_ip',
                'order_service_type_name',
                'order_src_name',
                'order_channel_name',
                'order_unit_money',
                'order_money',
                'order_booked_count',
                ['attribute'=>'order_booked_begin_time','value'=>date('Y-m-d H:i:s',$model->order_booked_begin_time)],
                ['attribute'=>'order_booked_end_time','value'=>date('Y-m-d H:i:s',$model->order_booked_end_time)],
                'order_address',
                'order_cs_memo',
            ],
            'enableEditMode'=>false,
    ]) ?>

</div>
