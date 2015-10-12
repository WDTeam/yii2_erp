<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '城市列表'), 'url' => ['operation-city/index']];
$this->params['breadcrumbs'][] = $city_name;
$this->title = $city_name.Yii::t('operation', 'Shop District Goods');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-shop-district-goods-index">


    <p>
        <?php /* echo Html::a(Yii::t('operation', 'Create {modelClass}', [
    'modelClass' => 'Operation Shop District Goods',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>
    <?php Pjax::begin(); 
        echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'operation_shop_district_goods_name',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('app', 'Operation'),
                'template' => ' {update} {delete} {listbtn}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>', 
                            Yii::$app->urlManager->createUrl(['operation-city/settinggoodsinfo', 'city_id' => $model::$city_id, 'goods_id' => $model->operation_goods_id, 'cityAddGoods' => 'editGoods']),
                            ['title' => Yii::t('yii', 'Update'), 'class' => 'btn btn-info btn-sm']
                        );
                    },
                    'delete' => function ($url, $model) {
                        return '';
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash"></span>',
                            Yii::$app->urlManager->createUrl(['operation-shop-district-goods/delete','id' => $model->operation_goods_id]),
                            ['title' => Yii::t('yii', 'Delete'), 'class' => 'btn btn-danger btn-sm', 'data-pjax'=>"0", 'data-method'=>"post", 'data-confirm'=>"您确定要删除此项吗？", 'aria-label'=>Yii::t('yii', 'Delete')]
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['operation-city/addgoods', 'city_id' => $city_id, 'cityAddGoods' => 'success'], ['class' => 'btn btn-success']),
//            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
