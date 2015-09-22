<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $category->operation_category_name.' - '.Yii::t('app', 'Category Types');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-category-type-index">

    <!--<h1><?php //= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app', 'Create').Yii::t('app', 'Category Types'), ['create', 'category_id'=> $category->id], ['class' => 'btn btn-success']) ?>
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
            'operation_category_name',
            [
               'attribute'=> 'created_at',
               'format'=>'html',
               'value' => function ($model){
                    if(empty($model->created_at)){
                        return '';
                    }else{
                        return date('Y-m-d H:i:s', $model->created_at);
                    }
               }
            ],
            [
               'attribute'=> 'updated_at',
               'format'=>'html',
               'value' => function ($model){
                    if(empty($model->created_at)){
                        return '';
                    }else{
                        return date('Y-m-d H:i:s', $model->created_at);
                    }
               }
            ],
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
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open"></span>', 
                            Yii::$app->urlManager->createUrl(['operation-category-type/view','id' => $model->id, 'category_id'=> $model->operation_category_id]),
                            ['title' => Yii::t('yii', 'View'), 'class' => 'btn btn-success btn-sm']
                        );
                    },
                    'update' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>', 
                            Yii::$app->urlManager->createUrl(['operation-category-type/update','id' => $model->id, 'category_id'=> $model->operation_category_id]), 
                            ['title' => Yii::t('yii', 'Edit'), 'class' => 'btn btn-info btn-sm',]
                        );
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash"></span>', 
                            Yii::$app->urlManager->createUrl(['operation-category-type/delete','id' => $model->id, 'category_id'=> $model->operation_category_id]),
                            ['title' => Yii::t('yii', 'Delete'), 'class' => 'btn btn-danger btn-sm', 'data-pjax'=>"0", 'data-method'=>"post", 'data-confirm'=>"您确定要删除此项吗？", 'aria-label'=>Yii::t('yii', 'Delete')]
                        );
                    },
                ],
            ],
        ],
    ]); ?>

</div>
