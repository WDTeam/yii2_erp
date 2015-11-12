<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\operation\OperationAdvertReleaseSearch $searchModel
 */

$this->title = '已发布广告管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-advert-release-index">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 已发布广告搜索</h3>
        </div>
        <div class="panel-body">
            <?php  echo $this->render('_search', ['model' => $searchModel, 'city_id' => $city_id]); ?>
        </div>
    </div>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'city_name',
            [
                'attribute'=> 'advert_release_order',
                'format'=>'raw',
                'value' => function ($model){
                    return Html::textInput('advert_release_order[]',$model->advert_release_order, ['class' => 'advert_release_orders_input', 'id' => $model->id]);
                }
            ],
            [
                'format' => 'raw',
                'label' => '广告名称',
                'value' => function ($dataProvider) {
                               return $dataProvider->operationAdvertContent->operation_advert_content_name;
                }
            ],
            [
                'format' => 'raw',
                'label' => '所属平台',
                'value' => function ($dataProvider) {
                               return $dataProvider->operationAdvertContent->platform_name;
                }
            ],
            [
                'format' => 'raw',
                'label' => '平台版本',
                'value' => function ($dataProvider) {
                               return $dataProvider->operationAdvertContent->platform_version_name;
                }
            ],
            [
                'format' => 'raw',
                'label' => '广告位置',
                'value' => function ($dataProvider) {
                               return $dataProvider->operationAdvertContent->position_name;
                }
            ],
            [
                'attribute' => 'starttime',
                'label' => '上线时间',
                //'format' => 
                //[
                    //'datetime',
                    //(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'
                //]
            ],
            [
                'attribute'=>'endtime',
                'label' => '下线时间',
                //'format'=>
                //[
                    //'datetime',
                    //(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'Y-m-d H:i:s'
                //]
            ], 
            [
                'attribute' => 'status',
                'value' => function ($dataProvider) {
                    if ($dataProvider->status == 0) {
                        return '未上线';
                    } elseif (($dataProvider->status == 1)) {
                        return '已上线';
                    } else {
                        return '已下线';
                    }
                }
            ],
            [
                'header' => Yii::t('app', 'Operation'),
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return '';
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open"></span>', 
                            Yii::$app->urlManager->createUrl(['/operation/operation-advert-content/view','id' => $model->id]),
                            ['title' => Yii::t('yii', 'View'), 'class' => 'btn btn-success btn-sm']
                        );
                    },
                    'update' => function ($url, $model) use ($city_id) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>', 
                            Yii::$app->urlManager->createUrl([
                                '/operation/operation-advert-release/update',
                                'id' => $model->id,
                                'city_id' => $city_id,
                            ]),
                            [
                                'title' => Yii::t('yii', 'Update')
                            ]
                        );
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash"></span>', 
                            Yii::$app->urlManager->createUrl(['/operation/operation-advert-release/delete','id' => $model->id]),
                            ['title' => 'Delete', 'data-pjax'=>"0", 'data-method'=>"post", 'data-confirm'=>"您确定要删除此项吗？", 'aria-label'=>Yii::t('yii', 'Delete')]
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
            //'type'=>'info',
            //'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            //'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
