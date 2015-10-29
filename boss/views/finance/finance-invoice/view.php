<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var dbbase\models\FinanceInvoice $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Finance Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-invoice-view">
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
            'finance_invoice_serial_number',
            'finance_invoice_customer_tel',
            'finance_invoice_worker_tel',
            'pay_channel_pay_id',
            'pay_channel_pay_title',
            'finance_invoice_pay_status',
            'admin_confirm_uid',
            'finance_invoice_enrolment_time:datetime',
            'finance_invoice_money',
            'finance_invoice_title',
            'finance_invoice_address',
            'finance_invoice_status',
            'finance_invoice_check_id',
            'finance_invoice_number',
            'finance_invoice_service_money',
            'finance_invoice_corp_email:email',
            'finance_invoice_corp_address',
            'finance_invoice_corp_name',
            'finance_invoice_district_id',
            'classify_id',
            'classify_title',
            'create_time',
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
