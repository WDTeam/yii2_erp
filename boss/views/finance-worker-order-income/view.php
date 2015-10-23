<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\finance\FinanceWorkerOrderIncome $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Worker Order Incomes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-worker-order-income-view">
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
            'worder_id',
            'order_id',
            'finance_worker_order_income_type',
            'finance_worker_order_income',
            'finance_worker_order_complete_time:datetime',
            'order_booked_count',
            'isSettled',
            'finance_worker_order_income_starttime:datetime',
            'finance_worker_order_income_endtime:datetime',
            'finance_settle_apply_id',
            'isdel',
            'updated_at',
            'created_at',
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
