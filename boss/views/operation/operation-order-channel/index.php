<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use boss\models\operation\OperationOrderChannelSearch;


$this->title = Yii::t('app', '订单渠道管理');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-order-channel-index">
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
    		'operation_order_channel_name',
    		[
    		'format' => 'raw',
    		'label' => '订单渠道类别',
    		'value' => function ($dataProvider) {
    		$date=OperationOrderChannelSearch::configorder();
    		return $date[$dataProvider->operation_order_channel_type];
    		},
    		'width' => "100px",
    		],
            'operation_order_channel_rate',
            'system_user_name', 
            'create_time:datetime',  

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['operation-order-channel/view','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('yii', '修改'),
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i>添加', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
