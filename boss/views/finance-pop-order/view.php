<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\FinancePopOrder $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Pop Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-pop-order-view">
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
            'finance_pop_order_number',
            'finance_order_channel_id',
            'finance_order_channel_title',
            'finance_pay_channel_id',
            'finance_pay_channel_title',
            'finance_pop_order_customer_tel',
            'finance_pop_order_worker_uid',
            'finance_pop_order_booked_time:datetime',
            'finance_pop_order_booked_counttime:datetime',
            'finance_pop_order_sum_money',
            'finance_pop_order_coupon_count',
            'finance_pop_order_coupon_id',
            'finance_pop_order_order2',
            'finance_pop_order_channel_order',
            'finance_pop_order_order_type',
            'finance_pop_order_status',
            'finance_pop_order_finance_isok',
            'finance_pop_order_discount_pay',
            'finance_pop_order_reality_pay',
            'finance_pop_order_order_time:datetime',
            'finance_pop_order_pay_time:datetime',
            'finance_pop_order_pay_status',
            'finance_pop_order_pay_title',
            'finance_pop_order_check_id',
            'finance_pop_order_finance_time:datetime',
            'create_time:datetime',
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
