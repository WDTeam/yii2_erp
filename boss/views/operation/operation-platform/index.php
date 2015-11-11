<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Platform').Yii::t('app', 'Manage');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-platform-index">

    <p>
        <?= Html::a(Yii::t('app', 'Create').Yii::t('app', 'Platform'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'header' => Yii::t('app', 'Order Number'),
                'class' => 'yii\grid\SerialColumn'
            ],

            'operation_platform_name',
            [
                'attribute' => 'created_at',
                'value' => function ($model){
                    return !empty($model->created_at) ? date('Y-m-d H:i:s', $model->created_at) : '';
                },
            ],[
                'attribute' => 'updated_at',
                'value' => function ($model){
                    return !empty($model->updated_at) ? date('Y-m-d H:i:s', $model->updated_at) : '';
                },
            ],

            [
                'header' => Yii::t('app', 'Operation'),
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {listbtn}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a(
                            '<span class="btn btn-primary glyphicon glyphicon-eye-open"> 查看</span>',
                            Yii::$app->urlManager->createUrl([
                                '/operation/operation-platform/view',
                                'id' => $model->id
                            ]),
                            [
                                'title' => Yii::t('yii', 'View'),
                            ]
                        );
                    },
                    'update' => function ($url, $model) {
                        return Html::a(
                            '<span class="btn btn-primary glyphicon glyphicon-pencil"> 编辑</span>',
                            Yii::$app->urlManager->createUrl([
                                '/operation/operation-platform/update',
                                'id' => $model->id
                            ]),
                            [
                                'title' => Yii::t('yii', 'Update'),
                            ]
                        );
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(
                            '<span class="btn btn-primary glyphicon glyphicon-trash"> 删除</span>',
                            Yii::$app->urlManager->createUrl([
                                '/operation/operation-platform/delete',
                                'id' => $model->id
                            ]),
                            [
                                'title' => Yii::t('yii', 'Delete'),
                                'data-pjax'=>"0",
                                'data-method'=>"post",
                                'data-confirm'=>"您确定要删除此项吗？",
                                'aria-label'=>Yii::t('yii', 'Delete')
                            ]
                        );
                    },
                    'listbtn' => function ($url, $model) {
                        return Html::a(
                            '<span class="btn btn-primary glyphicon glyphicon-list"> 查看版本列表</span>',
                            Yii::$app->urlManager->createUrl([
                                '/operation/operation-platform-version',
                                'platform_id' => $model->id
                            ]), 
                            [
                            'title' => Yii::t('yii', '平台版本'),
                            ]
                        );
                    },
                ],
            ],
        ],
    ]); ?>
</div>
