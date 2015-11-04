<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use boss\models\finance\FinanceOrderChannelSearch;

/**
 * @var yii\web\View $this
 * @var dbbase\models\FinanceOrderChannel $model
 */

$this->title = $model->finance_order_channel_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', '渠道管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="finance-order-channel-view">
    
    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
    		'buttons1'=>'{update}',
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
           // 'id',
            'finance_order_channel_name',
    		'finance_order_channel_rate',
    		[
    		'format' => 'raw',
    		'label' => '支付显示',
    		'attribute'=>'finance_order_channel_sort',
    		'type'=> DetailView::INPUT_RADIO_LIST,
    		'items'=>['1' => '确定', '2' => '取消'],
    		],
    		
    		[
    		'format' => 'raw',
    		'label' => '下单显示',
    		'attribute'=>'finance_order_channel_is_lock',
    		'type'=> DetailView::INPUT_RADIO_LIST,
    		'items'=>['1' => '确定', '2' => '取消'],
    		],
    		
    		[
    		'format' => 'raw',
    		'label' => '下单显示',
    		'attribute'=>'finance_order_channel_source',
    		'type'=> DetailView::INPUT_RADIO_LIST,
    		'items'=>FinanceOrderChannelSearch::get_sourcedate(1,2),
    		],
    		
    		[
    		'format' => 'raw',
    		'label' => '状态',
    		'attribute'=>'is_del',
    		'type'=> DetailView::INPUT_RADIO_LIST,
    		'items'=>['1' => '开启', '2' => '关闭'],
    		],
    	
         //   'create_time:datetime',
        ],
       
        'enableEditMode'=>true,
    ]) ?>

</div>
