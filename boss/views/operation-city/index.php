<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use boss\components\SearchBox;


/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\OperationCity $searchModel
 */

$this->title = Yii::t('app', Yii::t('app','Operation Cities'));
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-city-index">
<!--    <div class="page-header">
            <h1><?php //= Html::encode($this->title) ?></h1>
    </div>-->
    <?php
    echo SearchBox::widget([
        'action' => ['index'],
        'method' => 'POST',
        'options' => [],
        'type' => 'Field',
        'keyword_value' => isset($params['keyword']) ? $params['keyword'] : '',
        'keyword_options' => ['placeholder' => '搜索关键字', 'class' => 'form-control'],
        'submit_options' => ['class' => 'btn btn-default form-control'],
        'fields' => ['搜索字段', 'province_name' => '省份名称', 'city_name' => '城市名称'],
        'default' => isset($params['fields']) ? $params['fields'] : '',
    ]);
    ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Operation City',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            [
                'header' => Yii::t('app', 'Order Number'),
                'class' => 'yii\grid\SerialColumn'
            ],

//            'id',
            'province_name',
            'city_name',
            [
               'attribute'=> 'operation_city_is_online',
               'format'=>'html',
               'value' => function ($model){
                    Html::a('已上线', 
                            Yii::$app->urlManager->createUrl(['operation-city/upline','id' => $model->id]),
                            ['title' => '点击下线',]);
                    return $model->operation_city_is_online == 1 ? 
                            Html::a('已上线', Yii::$app->urlManager->createUrl(['operation-city/goline','id' => $model->id]), ['title' => '点击下线', 'class' => 'btn btn-success btn-sm']) : 
                            Html::a('已下线', Yii::$app->urlManager->createUrl(['operation-city/goline','id' => $model->id]), ['title' => '点击上线', 'class' => 'btn btn-danger btn-sm']);
               }
            ],
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
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('app', 'Operation'),
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open"></span>', 
                            Yii::$app->urlManager->createUrl(['operation-city/view','id' => $model->id]),
                            ['title' => Yii::t('yii', 'View'), 'class' => 'btn btn-success btn-sm']
                        );
                    },
                    'update' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>', 
                            Yii::$app->urlManager->createUrl(['operation-city/update','id' => $model->id]),
                            ['title' => Yii::t('yii', 'Update'), 'class' => 'btn btn-info btn-sm']
                        );
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash"></span>', 
                            Yii::$app->urlManager->createUrl(['operation-city/delete','id' => $model->id]),
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
            'after'=>false,//Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false,
            'footer' => false
        ],
    ]); Pjax::end(); ?>

</div>
