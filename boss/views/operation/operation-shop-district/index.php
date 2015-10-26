<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('operation', 'Operation Shop Districts');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Cities'), 'url' => ['/operation/operation-city/index']];
$this->params['breadcrumbs'][] = ['label' => $city_name];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-shop-district-index">

    <p>
        <?php /* echo Html::a(Yii::t('operation', 'Create {modelClass}', [
    'modelClass' => 'Operation Shop District',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'operation_area_name',
            'operation_shop_district_name',
//            'operation_city_id',
//            'operation_city_name',
//            'operation_shop_district_latitude_longitude:ntext',
//            'created_at', 
//            'updated_at', 
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('app', 'Operation'),
                'template' => '{update} {delete} {listbtn}',
                'buttons' => [
//                    'view' => function ($url, $model) {
//                        return Html::a(
//                            '<span class="glyphicon glyphicon-eye-open"></span>', 
//                            Yii::$app->urlManager->createUrl(['operation-shop-district/view','id' => $model->id]),
//                            ['title' => Yii::t('yii', 'View'), 'class' => 'btn btn-success btn-sm']
//                        );
//                    },
                    'update' => function ($url, $model) {
                        return Html::a(Yii::t('yii', '编辑'), ['/operation/operation-shop-district/update', 'id' => $model->id], ['class' => 'btn btn-info btn-sm']);
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>', 
                            Yii::$app->urlManager->createUrl(['/operation/operation-shop-district/update','id' => $model->id]),
                            ['title' => Yii::t('yii', 'Update'), 'class' => 'btn btn-info btn-sm']
                        );
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(Yii::t('yii', '删除'), ['/operation/operation-shop-district/delete', 'id' => $model->id], ['class' => 'btn btn-danger btn-sm', 'data-pjax'=>"0", 'data-method'=>"post", 'data-confirm'=>"您确定要删除此项吗？", 'aria-label'=>Yii::t('yii', 'Delete')]);
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash"></span>', 
                            Yii::$app->urlManager->createUrl(['/operation/operation-shop-district/delete','id' => $model->id]),
                            ['title' => Yii::t('yii', 'Delete'), 'class' => 'btn btn-danger btn-sm', 'data-pjax'=>"0", 'data-method'=>"post", 'data-confirm'=>"您确定要删除此项吗？", 'aria-label'=>Yii::t('yii', 'Delete')]
                        );
                    },      
                    'listbtn' => function ($url, $model) {
                        return '';
                        return Html::a('<span class="glyphicon glyphicon-list"></span>',Yii::$app->urlManager->createUrl(['/operation/operation-shop-district/goodslist','id' => $model->id]),['title' => Yii::t('yii', '商圈商品列表'), 'class' => 'btn btn-warning btn-sm']);
                    },
                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,




        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' - '.Html::encode($city_name).' </h3>',
            'type'=>'info',
            'before'=>''   //<span class="panel-title">'.Html::encode($city_name).' </span>
                .Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app', 'Add'), ['create'], ['class' => 'btn btn-success']),
//            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
