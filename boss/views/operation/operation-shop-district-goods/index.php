<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\operation\OperationShopDistrictGoodsSearch $searchModel
 */

$this->title = '上线城市管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-shop-district-goods-index">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 上线城市和项目搜索</h3>
        </div>
        <div class="panel-body">
            <?php echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'operation_city_name', 
            'operation_shop_district_name', 
            'operation_category_name', 
            'operation_shop_district_goods_name',
            'district_nums',
            [
                'header' => Yii::t('app', 'Operation'),
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{update} {offline}',
                'buttons' => [
                'update' => function ($url, $model) {
                    return Html::a(
                        '<span class="btn btn-primary">编辑</span>',
                        Yii::$app->urlManager->createUrl([
                        '/operation/operation-city/addgoods',
                        'city_id'  => $model->operation_city_id,
                        'goods_id' => $model->operation_goods_id,
                        'action'   => 'editGoods'
                        ]),
                        [
                            'title' => Yii::t('yii', 'Edit'),
                        ]
                    );
                },
                'offline' => function ($url, $model) {
                    return Html::a(
                        '<span class="btn btn-primary">下线</span>',
                        Yii::$app->urlManager->createUrl([
                            '/operation/operation-city/offline-city',
                            'city_id'  => $model->operation_city_id,
                            'goods_id' => $model->operation_goods_id,
                            'action'   => 'offline'
                        ]),
                        [
                            'title' => Yii::t('yii', 'Edit'),
                        ]
                    );
                },
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> 上线城市', ['/operation/operation-city/release'], ['class' => 'btn btn-success']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
