<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use boss\components\SearchBox;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('app', 'Operation Selected Service');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-goods-index">


    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'name' => 'id',
            ],
            'id',
            'selected_service_scene',
            'selected_service_area',
            'selected_service_sub_area',
            'selected_service_standard',
            'selected_service_price',
            'selected_service_unit',
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-eye-open"></span>',
                                Yii::$app->urlManager->createUrl(['operation-selected-service/view','id' => $model->id]),
                                ['title' => Yii::t('yii', 'View'), 'class' => 'btn btn-success btn-sm']
                            );
                        },
                        'update' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['operation-selected-service/update','id' => $model->id]), [
                                'title' => Yii::t('yii', 'Edit'), 'class' => 'btn btn-info btn-sm'
                            ]);},
                        'delete' => function ($url, $model) {
                            //return '';
                            return Html::a(
                                '<span class="glyphicon glyphicon-trash"></span>',
                                Yii::$app->urlManager->createUrl(['operation-selected-service/delete','id' => $model->id]),
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app', 'Add'), ['create'], ['class' => 'btn btn-success']),
//            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
