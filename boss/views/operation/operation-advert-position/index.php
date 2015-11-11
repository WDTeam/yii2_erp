<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Advert Position');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
    <div class="operation-advert-position-index">

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 广告搜索</h3>
            </div>
            <div class="panel-body">
                <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
            </div>
        </div>

        <p>
            <?= Html::a(Yii::t('app', 'Create').Yii::t('app', 'Advert Position'), ['create'], ['class' => 'btn btn-success']) ?>
        </p>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'header' => Yii::t('app', 'Order Number'),
                        'class'  => 'yii\grid\SerialColumn'
                    ],
                    [
                        'header'   => '位置名称',
                        'attribute'=> 'operation_advert_position_name'
                    ],
                    [
                        'header'   => '平台名称',
                        'attribute'=> 'operation_platform_name'
                    ],
                    [
                        'header'   => '版本名称', 
                        'attribute'=> 'operation_platform_version_name',
                    ],
                    [
                        'header'   => '宽度（像素）',
                        'attribute'=> 'operation_advert_position_width'
                    ],
                    [
                        'header'   => '高度（像素）',
                        'attribute'=> 'operation_advert_position_height'
                    ],
                    [
                        'header' => Yii::t('app', 'Operation'),
                        'class' => 'yii\grid\ActionColumn',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a(
                                    '<span class="btn btn-primary glyphicon glyphicon-eye-open"> 查看</span>',
                                    Yii::$app->urlManager->createUrl([
                                        '/operation/operation-advert-position/view',
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
                                        '/operation/operation-advert-position/update',
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
                                        '/operation/operation-advert-position/delete',
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
                        ],
                    ],
                ],
            ]); ?>
    </div>
</div>
