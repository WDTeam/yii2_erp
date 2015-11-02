<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\OperationServiceCardConsumeRecord $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '服务卡消费记录'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-service-card-consume-record-view">



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
            'customer_id',
            'customer_trans_record_transaction_id',
            'order_id',
            'order_code',
            'service_card_with_customer_id',
            'service_card_with_customer_code',
            'service_card_consume_record_front_money',
            'service_card_consume_record_behind_money',
            'service_card_consume_record_consume_type',
            'service_card_consume_record_business_type',
            'service_card_consume_record_use_money',
			[
				'label'=>'创建时间','value'=>date('Y-m-d',$model->created_at)
			],
			[
				'label'=>'更新时间','value'=>date('Y-m-d',$model->updated_at)
			],
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
        'enableEditMode'=>false,
    ]) ?>

</div>
