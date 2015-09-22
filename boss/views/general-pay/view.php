<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\GeneralPay $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'General Pays'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="general-pay-view">
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
            'general_pay_money',
            'general_pay_actual_money',
            'general_pay_source',
            'general_pay_source_name',
            'general_pay_mode',
            'general_pay_status',
            'general_pay_transaction_id',
            'general_pay_eo_order_id',
            'general_pay_memo',
            'general_pay_is_coupon',
            'admin_id',
            'general_pay_admin_name',
            'worker_id',
            'handle_admin_id',
            'general_pay_handle_admin_id',
            'general_pay_verify',
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
