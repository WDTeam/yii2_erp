<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\FinanceOrderChannel $model
 */

$this->title = $model->finance_order_channel_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', 'Finance Order Channels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-order-channel-view">
    
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
           // 'id',
            'finance_order_channel_name',
            'finance_order_channel_sort',
          //'finance_order_channel_is_lock',
    		[
    		'format' => 'raw',
    		'label' => '上下架',
    		'attribute'=>'finance_order_channel_is_lock',
    		'type'=> DetailView::INPUT_RADIO_LIST,
    		'items'=>['1' => '开启', '2' => '关闭'],
    		],
    	
         //   'create_time:datetime',
          //  'is_del',
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
