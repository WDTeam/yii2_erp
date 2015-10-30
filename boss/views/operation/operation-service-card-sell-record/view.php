<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\OperationServiceCardSellRecord $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '服务卡销售记录'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-service-card-sell-record-view">
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
//            'id',
            'service_card_sell_record_code',
            'customer_id',
            'customer_phone',
            'service_card_info_id',
            'service_card_info_name',
            'service_card_sell_record_money',
            'service_card_sell_record_channel_id',
            'service_card_sell_record_channel_name',
            'service_card_sell_record_status',
            'customer_trans_record_pay_mode',
            'pay_channel_id',
            'customer_trans_record_pay_channel',
            'customer_trans_record_transaction_id',
            'customer_trans_record_pay_money',
            'customer_trans_record_pay_account',
			[
				'label'=>'支付时间','value'=>date('Y-m-d',$model->customer_trans_record_paid_at)
			],
			[
				'label'=>'创建时间','value'=>date('Y-m-d',$model->created_at)
			],
			[
				'label'=>'更新时间','value'=>date('Y-m-d',$model->updated_at)
			],
//            'customer_trans_record_paid_at',
//            'created_at',
//            'updated_at',
//            'is_del',
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
