<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\OperationServiceCardWithCustomer $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '服务卡客户关系'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-service-card-with-customer-view">

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
            'service_card_sell_record_id',
            'service_card_sell_record_code',
            'service_card_info_id',
            'service_card_with_customer_code',
            'service_card_info_name',
            'customer_trans_record_pay_money',
            'service_card_info_value',
            'service_card_info_rebate_value',
            'service_card_with_customer_balance',
            'customer_id',
            'customer_phone',
            'service_card_info_scope',
//            'service_card_with_customer_buy_at',
//            'service_card_with_customer_valid_at',
//            'service_card_with_customer_activated_at',
			[
				'label'=>'购买日期','value'=>date('Y-m-d',$model->service_card_with_customer_buy_at)
			],
			[
				'label'=>'有效截止日期','value'=>date('Y-m-d',$model->service_card_with_customer_valid_at)
			],
			[
				'label'=>'激活日期','value'=>date('Y-m-d',$model->service_card_with_customer_activated_at)
			],
            'service_card_with_customer_status',
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
