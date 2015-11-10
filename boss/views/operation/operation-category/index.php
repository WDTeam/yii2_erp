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
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 品类搜索</h3>
        </div>
        <div class="panel-body">
            <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>


    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => Yii::t('app', 'Order Number'),
            ],

            [
                'header'=>"服务品类",
                'attribute'=>'operation_category_name'
            ],
            [
                'header'=>"服务项目",
                'attribute'=>'operation_goods_name'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('app', 'Operation'),
                'template' =>'{view} {update} {delete} {edit}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        if (!empty($model['goods_id'])) {
                            return Html::a(
                                '<span class="btn btn-primary">查看服务项目</span>',
                                Yii::$app->urlManager->createUrl([
                                    '/operation/operation-goods/view',
                                    'id' => $model['goods_id'],
                                ]),
                                [
                                    'title' => Yii::t('yii', 'Edit'),
                                ]
                            );
                        }
                    },
                    'update' => function ($url, $model) {
                        if (!empty($model['goods_id'])) {
                            return Html::a(
                                '<span class="btn btn-primary">编辑服务项目</span>',
                                Yii::$app->urlManager->createUrl([
                                    '/operation/operation-goods/update',
                                    'id' => $model['goods_id'],
                                ]),
                                [
                                    'title' => Yii::t('yii', 'Edit'),
                                ]
                            );
                        }
                    },
                    'edit' => function ($url, $model) {
                        return Html::a(
                            '<span class="btn btn-primary">编辑服务品类</span>',
                            Yii::$app->urlManager->createUrl([
                                '/operation/operation-category/update',
                                'id' => $model['id'],
                            ]),
                            [
                                'title' => Yii::t('yii', 'Edit'),
                            ]
                        );
                    },
                    'delete' => function ($url, $model) {
                        if (!empty($model['goods_id'])) {
                            return Html::a(
                                '<span class="btn btn-primary">删除服务项目</span>',
                                Yii::$app->urlManager->createUrl([
                                    '/operation/operation-goods/delete',
                                    'id' => $model['goods_id']
                                ]),
                                [
                                    'data-pjax'=>"0",
                                    'data-method'=>"post",
                                    'data-confirm'=>"您确定要删除此项吗？",
                                    'title' => Yii::t('yii', 'Delete'),
                                ]
                            );
                        }

                    }
                ],
            ],
            //[
                //'header' => Yii::t('app', 'Operation'),
                //'class' => 'yii\grid\ActionColumn',
                //'template' => '{view} {update} {delete} {listbtn}',
                //'buttons' => [
                    //'listbtn' => function ($url, $model) {
                        //return '';
                        //return Html::a(
                            //'<span class="glyphicon glyphicon-list"></span>',
                            //Yii::$app->urlManager->createUrl(['/operation/operation-category-type','category_id' => $model->id]),
                            //['title' => Yii::t('yii', '服务品类列表'), 'class' => 'btn btn-warning btn-sm',]
                        //);
                    //},
                //],
            //],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,

        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>
            Html::a('<i class="glyphicon glyphicon-plus"></i> 增加服务品类',
                ['create'],
                ['class' => 'btn btn-success']
            ).
            Html::a('<i class="glyphicon glyphicon-plus"></i> 增加服务项目',
                ['/operation/operation-goods/create'],
                ['class' => 'btn btn-success']
            ).
            Html::a('<i class="glyphicon glyphicon-list"></i> 规格管理',
                ['/operation/operation-spec'],
                [
                    'class' => 'btn btn-success',
                    'data-pjax'=>"0",
                ]
            ),
            'after'=>false,
            'showFooter'=>false,
            'footer' => false
        ],
    ]); Pjax::end(); ?>

</div>
