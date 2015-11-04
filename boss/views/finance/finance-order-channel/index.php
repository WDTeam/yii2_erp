<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\tabs\TabsX;
use dbbase\models\finance\FinanceOrderChannel;
use boss\models\finance\FinanceOrderChannelSearch;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\FinanceOrderChannelSearch $searchModel
 */


?>
<div class="finance-order-channel-index hideTemp">
    <div class="page-header">
    </div>
    <p>
     <?php 
     $this->title = Yii::t('boss', '订单渠道管理');
     $this->params['breadcrumbs'][] = $this->title;
     Pjax::begin();
    $ertyy= GridView::widget([
     		'dataProvider' => $dataProvider,
     		//'filterModel' => $searchModel,
     		'columns' => [
     		['class' => 'yii\grid\SerialColumn'],
     
     		//'id',
     		'finance_order_channel_name',
    		'finance_order_channel_rate',
    		[
    		'format' => 'raw',
    		'label' => '支付显示',
    		'value' => function ($dataProvider) {
    			return $dataProvider->finance_order_channel_sort==1 ? '<font cloro:red>确定</font>':'取消';
    		},
    		'width' => "100px",
    		],
    		
    		[
    		'format' => 'raw',
    		'label' => '下单显示',
    		'value' => function ($dataProvider) {
    			return $dataProvider->finance_order_channel_is_lock==1 ? '<font cloro:red>确定</font>':'取消';
    		},
    		'width' => "100px",
    		],
    		[
    		'format' => 'raw',
    		'label' => '来源',
    		'value' => function ($dataProvider) {
    			return FinanceOrderChannelSearch::get_sourcedate($dataProvider->finance_order_channel_source,1);
    		},
    		'width' => "100px",
    		],
    		
    		[
    		'format' => 'raw',
    		'label' => '状态',
    		'value' => function ($dataProvider) {
    			return $dataProvider->finance_order_channel_is_lock ? '开启' : '关闭';
    		},
    		'width' => "100px",
    		],
     		'create_time:datetime',
     		[
     		'class' => 'yii\grid\ActionColumn',
     		'template' =>'{view} {update}',
     		'buttons' => [
     		'update' => function ($url, $model) {
     	return Html::a('<span class="btn btn-primary">编辑</span>', Yii::$app->urlManager->createUrl(['finance/finance-order-channel/view','id' => $model->id,'edit'=>'t']), [
     			'title' => Yii::t('yii', 'Edit'),
     			]);},
            'view' => function ($url, $model) {
        return Html::a('<span class="btn btn-primary">查看</span>', Yii::$app->urlManager->createUrl(['finance/finance-order-channel/view','id' => $model->id,'edit'=>'t']), [
                
                ]);},
     
     	],
     	],
     	],
     	'responsive'=>true,
     	'hover'=>true,
     	'condensed'=>true,
     	'floatHeader'=>true,
     	'panel' => [
     	'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
     	'type'=>'info',
     	'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> 增加', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          
     	'showFooter'=>false
     	],
     	]);
$items = [
[
'label'=>'<i class="glyphicon glyphicon-list-alt"></i> 订单渠道管理',
'content'=>$ertyy,
'active'=>true,
],
[
'label'=>'<i class="glyphicon glyphicon-king"></i> 支付渠道管理',
'content'=>'',
'active'=>false,
'url' => ['finance/finance-pay-channel/index']
]
];
    echo TabsX::widget([
    		'items'=>$items,
    		'position'=>TabsX::POS_ABOVE,
    		'bordered'=>true,
    		'encodeLabels'=>false
    		]);
    Pjax::end(); ?> 
      
      
      
    </p>


</div>
