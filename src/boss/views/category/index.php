<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Categories');//'Categories';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a(Yii::t('app', 'Create Categories'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header' => '序号'],
            'catename',
            'description:ntext',
            
//            ['class' => 'yii\grid\ActionColumn', 'header' => Yii::t('app', 'Operation')],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{courseware-list}{courseware-add}',
                'buttons' => [
                    'courseware-list' =>function($url, $model){
                        return Html::a('课件管理', [
                        'courseware/index', 
                        'classify_id'=>$model->cateid
                        ], [
                            'title' => Yii::t('yii', '课件管理'),
                        ]);
                    }
                ],
            ],
            [
            'class' => 'yii\grid\ActionColumn',
            'buttons' => [
                    'view' => function($url, $model){return $model->cateid != '1' ? Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id'=>$model->cateid], ['title' => Yii::t('yii', 'Add'),]): '';},
                    'update' => function($url, $model){
                        return $model->cateid != '1' ? Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id'=>$model->cateid], ['title' => Yii::t('yii', 'Edit'), 'data-pjax'=>"0", 'data-method'=>"post", 'data-confirm'=>"您确定要编辑此项吗？", 'aria-label'=>Yii::t('yii', 'Edit')]): '';},
                   // 'delete' => function($url, $model){return $model->cateid != '1' ? Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id'=>$model->cateid], ['title' => Yii::t('yii', 'Delete'),]): '';},
                     'delete' => function ($url, $model){
                     return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl([
                        'category/delete',
                        'id' => $model->cateid,
                        /*'classify_id'=>$model->classify_id,*/]), ['title' => Yii::t('yii', 'Delete'), 'data-pjax'=>"0", 'data-method'=>"post", 'data-confirm'=>"您确定要删除此项吗？", 'aria-label'=>Yii::t('yii', 'Delete')]);
                       },
                ],
            ],
        ],
    ]); ?>

</div>
