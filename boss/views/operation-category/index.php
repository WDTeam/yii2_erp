<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\OperationCategory $searchModel
 */

$this->title = Yii::t('app', 'Operation Categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-category-index">
<!--    <div class="page-header">
            <h1><?php //= Html::encode($this->title) ?></h1>
    </div>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Operation Category',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => Yii::t('app', 'Order Number'),
            ],

//            'id',
            'operation_category_name',
            [
               'attribute'=> 'created_at',
               'format'=>'html',
               'value' => function ($model){
                    if(empty($model->created_at)){
                        return '';
                    }else{
                        return date('Y-m-d H:i:s', $model->created_at);
                    }
               }
            ],
            [
               'attribute'=> 'updated_at',
               'format'=>'html',
               'value' => function ($model){
                    if(empty($model->updated_at)){
                        return '';
                    }else{
                        return date('Y-m-d H:i:s', $model->updated_at);
                    }
               }
            ],
            [
                'header' => Yii::t('app', 'Operation'),
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>', 
                            Yii::$app->urlManager->createUrl(['operation-category/update','id' => $model->id]), 
                            ['title' => Yii::t('yii', 'Edit'),]
                        );
                    },
                    'list' => function ($url, $model) {
                        return Html::a(
                            '<span class="fa fa-list"></span>', 
                            Yii::$app->urlManager->createUrl(['operation-category-type/list','id' => $model->id]), 
                            ['title' => Yii::t('yii', 'Edit'),]
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
            'after'=>false,//Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app', 'Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false,
            'footer' => false
        ],
    ]); Pjax::end(); ?>

</div>
