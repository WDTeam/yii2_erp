<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\FinanceCompensate $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Compensates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-compensate-view">
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
            'finance_compensate_oa_num',
            'finance_compensate_pay_money',
            'finance_compensate_cause',
            'finance_compensate_tel',
            'finance_compensate_money',
            'finance_pay_channel_id',
            'finance_pay_channel_name',
            'finance_order_channel_id',
            'finance_order_channel_name',
            'finance_compensate_discount',
            'finance_compensate_pay_create_time:datetime',
            'finance_compensate_pay_flow_num',
            'finance_compensate_pay_status',
            'finance_compensate_worker_id',
            'finance_compensate_worker_tel',
            'finance_compensate_proposer',
            'finance_compensate_auditor',
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
