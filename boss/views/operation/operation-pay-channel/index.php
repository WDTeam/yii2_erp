<?php
/**
* 控制器 支付渠道管理
* ==========================
* 北京一家洁 版权所有 2015-2018 
* ----------------------------
* 这不是一个自由软件，未经授权不许任何使用和传播。
* ==========================
* @date: 2015-11-10
* @author: peak pan 
* @version:1.0
*/
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use boss\models\operation\OperationPayChannelSearch;


$this->title = Yii::t('app', '支付渠道管理');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-pay-channel-index">
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
    		[
    		'format' => 'raw',
    		'label' => '订单渠道类别',
    		'value' => function ($dataProvider) {
    			$date=OperationPayChannelSearch::configorder();
    			return $date[$dataProvider->operation_pay_channel_type];
    		},
    		'width' => "100px",
    		],
    		'operation_pay_channel_name',
            'operation_pay_channel_rate',
            'system_user_name', 
            'create_time:datetime', 
            [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view} {update}',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['operation/operation-pay-channel/view','id' => $model->id,'edit'=>'t']), [
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
