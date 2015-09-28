<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\tabs\TabsX;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\FinancePayChannelSearch $searchModel
 */
?>
<div class="finance-pay-channel-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php 
     $this->title = Yii::t('boss', '支付渠道管理');
     $this->params['breadcrumbs'][] = $this->title;
     Pjax::begin();
   $paychannel= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'finance_pay_channel_name',
            'finance_pay_channel_rank',
	   		[
	   		'format' => 'raw',
	   		'label' => '上下架',
	   		'value' => function ($dataProvider) {
	   			return $dataProvider->finance_pay_channel_is_lock ? '<font cloro:red>上架</font>' : '下架';
	   		},
	   		'width' => "100px",
	   		],
            'create_time:datetime',
//            'is_del', 

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['finance-pay-channel/view','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('yii', 'Edit'),
                                                  ]);}

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
'content'=>'',
'active'=>false,
'url' => ['finance-order-channel/index']
],
[
'label'=>'<i class="glyphicon glyphicon-king"></i> 支付渠道管理',
'content'=>$paychannel,
'active'=>true,

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
