<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\OperationServiceCardWithCustomer $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Service Card With Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-service-card-with-customer-view">
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
            'service_card_sell_record_id',
            'service_card_sell_record_code',
            'server_card_info_id',
            'service_card_with_customer_code',
            'server_card_info_name',
            'customer_trans_record_pay_money',
            'server_card_info_value',
            'service_card_info_rebate_value',
            'service_card_with_customer_balance',
            'customer_id',
            'customer_phone',
            'server_card_info_scope',
            'service_card_with_customer_buy_at',
            'service_card_with_customer_valid_at',
            'service_card_with_customer_activated_at',
            'service_card_with_customer_status',
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
