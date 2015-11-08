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
<div class="operation-selected-service-index">

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'name' => 'id',
            ],
            [
                'header'=>"编号",
                'attribute'=>'id',
            ],
            [
                'header'=>"场景",
                'attribute'=>'selected_service_scene',
            ],
            [
                'header'=>"区域",
                'attribute'=>'selected_service_area',
            ],
            [
                'header'=>"子区域",
                'attribute'=>'selected_service_sub_area',
            ],
            [
                'header'=>"清洁标准",
                'attribute'=>'selected_service_standard',
            ],
            //'selected_service_price',
            [
                'header'=>"时长(分钟)",
                'attribute'=>'selected_service_unit',
            ],
            //[
                //'header'=>"面积标准(平米)",
                //'attribute'=>'selected_service_unit',
            //],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-eye-open"></span>',
                                Yii::$app->urlManager->createUrl(['/operation/operation-selected-service/view','id' => $model->id]),
                                ['title' => Yii::t('yii', 'View'), 'class' => 'btn btn-success btn-sm']
                            );
                        },
                        'update' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['/operation/operation-selected-service/update','id' => $model->id]), [
                                'title' => Yii::t('yii', 'Edit'), 'class' => 'btn btn-info btn-sm'
                            ]);},
                        'delete' => function ($url, $model) {
                            //return '';
                            return Html::a(
                                '<span class="glyphicon glyphicon-trash"></span>',
                                Yii::$app->urlManager->createUrl(['/operation/operation-selected-service/delete','id' => $model->id]),
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
        'striped'=>false,

        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>
            Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app', 'Add'), ['create'], ['class' => 'btn btn-success']).
            Html::a('面积小于100平米', ['index?OperationSelectedService[selected_service_area_standard]=1'], ['class' => 'btn '.$searchModel::setBtnCss(1), 'style' => 'margin-right:10px']).
            Html::a('面积大于100平米', ['index?OperationSelectedService[selected_service_area_standard]=2'], ['class' => 'btn '.$searchModel::setBtnCss(2), 'style' => 'margin-right:10px']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
