<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\OperationCity $searchModel
 */

$this->title = Yii::t('app', 'Operation And Cities').'管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-city-index">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 城市搜索</h3>
        </div>
        <div class="panel-body">
            <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>

    <?php Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'header' => Yii::t('app', 'Order Number'),
                'class' => 'yii\grid\SerialColumn'
            ],

            [
                'header'=>"省(省级市)",
                'attribute'=>'province_name',
            ],
            [
                'header'=>"市",
                'attribute'=>'city_name',
            ],
            [
                'header'=>"商圈数量",
                'attribute'=> 'shopdistrict',
                'format'=>'html',
                'value' => function ($model) use ($ShopDistrictModel){
                    return $ShopDistrictModel::getCityShopDistrictNum($model->city_id);
                    //return date('Y-m-d H:i:s', $model->created_at);
                }
            ],
//            [
//               'attribute'=> 'operation_city_is_online',
//               'format'=>'html',
//
////                'value' => function ($model){
////                    Html::a('已上线', 
////                            Yii::$app->urlManager->createUrl(['operation-city/upline','id' => $model->id]),
////                            ['title' => '点击下线',]);
////                    return $model->operation_city_is_online == 1 ? 
////                            Html::a('已上线', Yii::$app->urlManager->createUrl(['operation-city/goline','id' => $model->id]), ['title' => '点击下线', 'class' => 'btn btn-success btn-sm']) : 
////                            Html::a('已下线', Yii::$app->urlManager->createUrl(['operation-city/goline','id' => $model->id]), ['title' => '点击上线', 'class' => 'btn btn-danger btn-sm']);
////               }
//               'value' => function ($model){
//                    return $model->operation_city_is_online == 1 ? 
//                            Html::a('已开通', 'javascript:void(0)', ['title' => '', 'class' => 'btn btn-success btn-sm']) :
//                            Html::a('未开通', 'javascript:void(0)', ['title' => '', 'class' => 'btn btn-danger btn-sm']);
//               }
//            ],
//            [
//               'attribute'=> 'category',
//               'format'=>'html',
//               'value' => function ($model){
//                    return OperationShopDistrictGoods::getCityShopDistrictNum($model->city_id);
// //                   return date('Y-m-d H:i:s', $model->created_at);
//               }
//            ],
//
//            [
//               'attribute'=> 'created_at',
//               'format'=>'html',
//               'value' => function ($model){
//                    if(empty($model->created_at)){
//                        return '';
//                    }else{
//                        return date('Y-m-d H:i:s', $model->created_at);
//                    }
//               }
//            ],
//            [
//               'attribute'=> 'updated_at',
//               'format'=>'html',
//               'value' => function ($model){
//                    if(empty($model->updated_at)){
//                        return '';
//                    }else{
//                        return date('Y-m-d H:i:s', $model->updated_at);
//                    }
//               }
//            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('app', 'Operation'),
                'template' => '{view} {update} {delete} {listbtn}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a(Yii::t('yii', 'View'), ['/operation/operation-city/view','id' => $model->id], ['class' => 'btn btn-success btn-sm']);
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open"></span>', 
                            Yii::$app->urlManager->createUrl(['/operation/operation-city/view','id' => $model->id]),
                            [
                                'title' => Yii::t('yii', 'View'),
                                'class' => 'btn btn-success btn-sm'
                            ]
                        );
                    },
                    'update' => function ($url, $model) {
                        return '';
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>', 
                            Yii::$app->urlManager->createUrl(['/operation/operation-city/update','id' => $model->id]),
                            [
                                'title' => Yii::t('yii', 'Update'),
                                'class' => 'btn btn-info btn-sm'
                            ]
                        );
                    },
                    'delete' => function ($url, $model) {
                        return '';
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash"></span>', 
                            Yii::$app->urlManager->createUrl(['/operation/operation-city/delete','id' => $model->id]),
                            [
                                'title' => Yii::t('yii', 'Delete'),
                                'class' => 'btn btn-danger btn-sm',
                                'data-pjax'=>"0",
                                'data-method'=>"post",
                                'data-confirm'=>"您确定要删除此项吗？",
                                'aria-label'=>Yii::t('yii', 'Delete')
                            ]
                        );
                    },
                    'listbtn' => function ($url, $model) {
                        return Html::a(Yii::t('yii', '商圈列表'), 
                        [
                            '/operation/operation-shop-district',
                            'city_id' => $model->city_id
                        ],
                        [
                            'class' => 'btn btn-warning btn-sm',
                            'data-pjax'=>'0',
                        ]
                    );
                        //return Html::a('<span class="glyphicon glyphicon-list"></span>',Yii::$app->urlManager->createUrl(['/operation/operation-shop-district','city_id' => $model->city_id]),['title' => Yii::t('yii', '商圈列表'), 'class' => 'btn btn-warning btn-sm']); //.Html::a('<span class="glyphicon glyphicon-list"></span>',Yii::$app->urlManager->createUrl(['operation-shop-district-goods','city_id' => $model->city_id]),['title' => Yii::t('yii', '城市商品列表'), 'class' => 'btn btn-warning btn-sm']);
                    },
                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,




        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '. Yii::t('app', Yii::t('app','Operation Cities')).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app', '增加城市'), ['create'], ['class' => 'btn btn-success']), 
            'after'=>false,//Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false,
            'footer' => false
        ],
    ]); Pjax::end(); ?>

</div>
