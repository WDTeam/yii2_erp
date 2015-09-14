<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

//use yii\grid\GridView;
//use yii\widgets\Pjax;




/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\models\CoursewareSearch $searchModel
 */

$this->title = $category->catename.Yii::t('app', 'Coursewares');
$this->params['breadcrumbs'][] = Html::a($category->catename, ['category/index']);
$this->params['breadcrumbs'][] = Yii::t('app', 'Coursewares');
//$this->params['breadcrumbs'][] =['label'=>$model->cateid, 'url' => ['view','id'=>$model->cateid]];
?>
<div class="courseware-index">
<!--    <div class="page-header">
            <h1><?php// Html::encode($this->title) ?></h1>
    </div>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Courseware',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>
    <?php  
    echo GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header' => '序号'],

//            'id',
            [
                'attribute' => 'order_number',
                'format' => 'raw',
                'label' => Yii::t('app', 'Orders'),
                'value' => function($model){
                    return '<div class="btn-group">'.
                           Html::a('<i class="fa fa-fw fa-arrow-up"></i>','javascript:void(0);',['class' => 'saveOrders btn btn-info', 'cid' => $model->id, 'direction' =>'up', 'title' => Yii::t('app', 'go up')]).
                           
                           Html::a('<i class="fa fa-fw fa-arrow-down"></i>','javascript:void(0);',['class' => 'saveOrders btn btn-info', 'cid' => $model->id, 'direction' =>'down', 'title' => Yii::t('app', 'go down')]).
                           '</div>';
                },
            ],
            [
               'attribute'=> 'image',
               'format'=>'html',
               'value' => function ($data){
                    $str = Html::img(Yii::getAlias('@web'). $data['image'],['height' => '34', 'alt'=>$data->name, 'border' => '0']);
                    return Html::a($str, 'javascript:void(0);', ['class' => 'showImg', 'url' => $data['image']]);
               }
            ],
//            'url:url',
            'name',
//            'pv',
//            'order_number', 
//            'classify_id',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{courseware-list}{courseware-add}',
                'buttons' => [
                    'courseware-list' =>function($url, $model){
                        return Html::a('试题管理', [
                        'question/index', 
                        'courseware_id'=>$model->id, 
                        'classify_id'=>$model->classify_id,
                        ], [
                            'title' => Yii::t('yii', '试题管理'),
                        ]);
                    }
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl([
                        'courseware/update',
                        'id' => $model->id,
                        'classify_id'=>$model->classify_id,]), ['title' => Yii::t('yii', 'Edit'), 'data-pjax'=>"0", 'data-method'=>"post", 'data-confirm'=>"您确定要编辑此项吗？", 'aria-label'=>Yii::t('yii', 'Edit')]);
                    },
                'view' => function ($url, $model){
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Yii::$app->urlManager->createUrl([
                        'courseware/view',
                        'id' => $model->id,
                        'classify_id'=>$model->classify_id,]), ['title' => Yii::t('yii', 'View'),]);
                },
                'delete' => function ($url, $model){
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl([
                        'courseware/delete',
                        'id' => $model->id,
                        'classify_id'=>$model->classify_id,]), ['title' => Yii::t('yii', 'Delete'), 'data-pjax'=>"0", 'data-method'=>"post", 'data-confirm'=>"您确定要删除此项吗？", 'aria-label'=>Yii::t('yii', 'Delete')]);
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create', 'classify_id'=>$classify_id], ['class' => 'btn btn-success']),
            'after'=>false,//Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false,
        ],
    ]);  ?>
</div>
