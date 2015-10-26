<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\operation\OperationServerCardOrder $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Server Card Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-server-card-order-view">
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
            'usere_id',
            'order_customer_phone',
            'server_card_id',
            'card_name',
            'card_type',
            'card_level',
            'par_value',
            'reb_value',
            'order_money',
            'order_src_id',
            'order_src_name',
            'order_channel_id',
            'order_channel_name',
            'order_lock_status',
            'order_status_id',
            'order_status_name',
            'created_at',
            'updated_at',
            'order_pay_type',
            'pay_channel_id',
            'pay_channel_name',
            'order_pay_flow_num',
            'order_pay_money',
            'paid_at',
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
