<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Category Types');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-category-type-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'header' => Yii::t('app', 'Order Number'),
                'class' => 'yii\grid\SerialColumn'
            ],

//            'id',
            'operation_category_type_name',
            'operation_category_type_english_name',
            'created_at',
//            'operation_category_id',
//            'operation_category_name',
//            'operation_category_type_introduction:ntext',
//             'operation_category_type_english_name',
            // 'operation_category_type_start_time',
            // 'operation_category_type_end_time',
            // 'operation_category_type_service_time_slot:ntext',
            // 'operation_category_type_service_interval_time:datetime',
            // 'operation_price_strategy_id',
            // 'operation_price_strategy_name',
            // 'operation_category_type_price',
            // 'operation_category_type_balance_price',
            // 'operation_category_type_additional_cost',
            // 'operation_category_type_lowest_consume',
            // 'operation_category_type_price_description:ntext',
            // 'operation_category_type_market_price',
            // 'operation_tags:ntext',
            // 'operation_category_type_app_ico:ntext',
            // 'operation_category_type_pc_ico:ntext',
            // 'created_at',
            // 'updated_at',

            [
                'header' => Yii::t('app', 'Operation'),
                'class' => 'yii\grid\ActionColumn'
            ],
        ],
    ]); ?>

</div>
