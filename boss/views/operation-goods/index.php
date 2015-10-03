<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('app', 'Operation Goods');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-goods-index">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Operation Goods',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'operation_goods_name',
            'operation_category_id',
            'operation_category_ids',
            'operation_category_name',
//            'operation_goods_introduction:ntext', 
//            'operation_goods_english_name', 
//            'operation_goods_start_time', 
//            'operation_goods_end_time', 
//            'operation_goods_service_time_slot:ntext', 
//            'operation_goods_service_interval_time:datetime', 
//            'operation_price_strategy_id', 
//            'operation_price_strategy_name', 
//            'operation_goods_price', 
//            'operation_goods_balance_price', 
//            'operation_goods_additional_cost', 
//            'operation_goods_lowest_consume', 
//            'operation_goods_price_description:ntext', 
//            'operation_goods_market_price', 
//            'operation_tags:ntext', 
//            'operation_goods_app_ico:ntext', 
//            'operation_goods_type_pc_ico:ntext', 
//            'created_at', 
//            'updated_at', 

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['operation-goods/view','id' => $model->id,'edit'=>'t']), [
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
