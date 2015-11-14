<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use dbbase\models\finance\FinanceRefund;
/**
 * @var yii\web\View $this
 * @var dbbase\models\FinanceRefund $model
 */

$this->title = $model->finance_refund_tel;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '确认财务审核'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-refund-view">
    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
    		'buttons1'=>'<a href="javascript:history.go(-1)">返回</a>',
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
    		'finance_refund_code',
    		'finance_refund_tel',
    		'finance_refund_money',
    		[
    		'attribute' => 'finance_refund_stype',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=>$model->finance_refund_stype==1 ?'用户取消':'未知',
    		],
    		'finance_refund_reason',
    		'finance_refund_discount',
    		'finance_refund_pay_create_time:datetime',
    		'finance_pay_channel_title',
    		[
    		'attribute' => 'finance_refund_pay_status',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=>$model->finance_refund_pay_status==1?'已支付':'未支付',
    		],
    		'finance_refund_pay_flow_num',
    		'finance_order_channel_title',
    		[
    		'attribute' => 'finance_refund_worker_id',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		
    		'value'=>FinanceRefund::get_adminname($model->finance_refund_worker_id),
    		],
    		'finance_refund_worker_tel',
    		'finance_refund_check_name',
		    [
		    'attribute' => 'isstatus',
		    'type' => DetailView::INPUT_TEXT,
		    'displayOnly' => true,
		    'value'=>FinanceRefund::get_status($model->isstatus),
		    ],
    		'create_time:datetime',
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
