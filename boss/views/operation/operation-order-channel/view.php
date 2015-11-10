<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

use boss\models\operation\OperationOrderChannelSearch;
/**
 * @var yii\web\View $this
 * @var dbbase\models\operation\OperationOrderChannel $model
 */

$this->title = $model->operation_order_channel_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '渠道管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-order-channel-view">
    
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
            'operation_order_channel_name',
    		[
			'format' => 'raw',
			'label' => '订单渠道类别',
			'attribute'=>'operation_order_channel_type',
			'type'=> DetailView::INPUT_RADIO_LIST,
			'items'=>OperationOrderChannelSearch::configorder(),
			],
    		
            'operation_order_channel_rate',
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
