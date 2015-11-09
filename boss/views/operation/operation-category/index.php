<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\OperationCategory $searchModel
 */

$this->title = Yii::t('app', 'Operation And Categories').'管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-category-index">
<!--    <div class="page-header">
            <h1><?php //= Html::encode($this->title) ?></h1>
    </div>-->
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

<!--    <p>-->
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Operation Category',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
<!--    </p>-->

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => Yii::t('app', 'Order Number'),
            ],

//           'id',
            [
                'header'=>"服务品类",
                'attribute'=>'operation_category_name'
            ],
            [
                'header'=>"服务项目",
                'attribute'=>'operation_goods_name'
            ],
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
                'header' => Yii::t('app', 'Operation'),
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {listbtn}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        if(empty($model['goods_id'])){

                        }else {
                            return Html::a(Yii::t('yii', 'View'), ['/operation/operation-goods/view','id' => $model['goods_id']], ['class' => 'btn btn-success btn-sm']);
                            return Html::a(
                                '<span class="glyphicon glyphicon-eye-open">创建</span>',
                                Yii::$app->urlManager->createUrl(['/operation/operation-goods/view','id' => $model['goods_id']]),
                                ['title' => Yii::t('yii', 'View'), 'class' => 'btn btn-success btn-sm']
                            );
                        }
                    },
                    'update' => function ($url, $model) {
                        if(empty($model['goods_id'])){

                        }else {
                             return Html::a(Yii::t('yii', '编辑'), ['/operation/operation-goods/update', 'id' => $model['goods_id']], ['class' => 'btn btn-info btn-sm']);
                            return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>',
                            Yii::$app->urlManager->createUrl(['/operation/operation-goods/update', 'id' => $model['goods_id']]),
                            ['title' => Yii::t('yii', 'Edit'), 'class' => 'btn btn-info btn-sm',]
                             );
                        }
                    },
                    'delete' => function ($url, $model) {
                        if(empty($model['goods_id'])){

                        }else{
                            return Html::a(Yii::t('yii', '删除'), ['/operation/operation-goods/delete', 'id' => $model['goods_id']], ['class' => 'btn btn-danger btn-sm', 'data-pjax'=>"0", 'data-method'=>"post", 'data-confirm'=>"您确定要删除此项吗？", 'aria-label'=>Yii::t('yii', 'Delete')]);
                            return Html::a(
                                '<span class="glyphicon glyphicon-trash">删除</span>',
                                Yii::$app->urlManager->createUrl(['/operation/operation-goods/delete','id' => $model['goods_id']]),
                                ['title' => Yii::t('yii', 'Delete'), 'class' => 'btn btn-danger btn-sm', 'data-pjax'=>"0", 'data-method'=>"post", 'data-confirm'=>"您确定要删除此项吗？", 'aria-label'=>Yii::t('yii', 'Delete')]
                            );
                        }
                    },
                    'listbtn' => function ($url, $model) {
                        return '';
                        return Html::a(
                            '<span class="glyphicon glyphicon-list"></span>',
                            Yii::$app->urlManager->createUrl(['/operation/operation-category-type','category_id' => $model->id]),
                            ['title' => Yii::t('yii', '服务品类列表'), 'class' => 'btn btn-warning btn-sm',]
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
            'before'=>
            Html::a('<i class="glyphicon glyphicon-plus"></i> 增加服务品类', ['create'], ['class' => 'btn btn-success']).
            Html::a('<i class="glyphicon glyphicon-plus"></i> 增加服务项目', ['/operation/operation-goods/create'], ['class' => 'btn btn-success']).
            Html::a('<i class="glyphicon glyphicon-list"></i> 规格管理', ['/operation/operation-spec'], ['class' => 'btn btn-success']),
            'after'=>false,//Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app', 'Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false,
            'footer' => false
        ],
    ]); Pjax::end(); ?>

</div>
