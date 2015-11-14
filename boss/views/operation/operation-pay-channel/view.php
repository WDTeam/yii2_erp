<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use boss\models\operation\OperationPayChannelSearch;
/**
 * @var yii\web\View $this
 * @var dbbase\models\operation\OperationPayChannel $model
 */

$this->title = $model->operation_pay_channel_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '支付渠道管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-pay-channel-view">

    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
    		'buttons1'=>'{update}',
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
    		[
    		'attribute' => 'id',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=>$model->id,
    		],
            'operation_pay_channel_name',
			[
			'format' => 'raw',
			'label' => '支付渠道类别',
			'attribute'=>'operation_pay_channel_type',
			'type'=> DetailView::INPUT_RADIO_LIST,
			'items'=>OperationPayChannelSearch::configorder(),
			],
            'operation_pay_channel_rate',
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
