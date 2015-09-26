<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Platform').Yii::t('app', 'Manage');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-platform-index">

    <!--<h1><?php //= Html::encode($this->title) ?></h1>-->

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

//            'id',
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
                            '<span class="glyphicon glyphicon-eye-open"></span>', 
                            Yii::$app->urlManager->createUrl(['operation-platform/view','id' => $model->id]),
                            ['title' => Yii::t('yii', 'View'), 'class' => 'btn btn-success btn-sm']
                        );
                    },
                    'update' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>', 
                            Yii::$app->urlManager->createUrl(['operation-platform/update','id' => $model->id]),
                            ['title' => Yii::t('yii', 'Update'), 'class' => 'btn btn-info btn-sm']
                        );
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash"></span>', 
                            Yii::$app->urlManager->createUrl(['operation-platform/delete','id' => $model->id]),
                            ['title' => Yii::t('yii', 'Delete'), 'class' => 'btn btn-danger btn-sm', 'data-pjax'=>"0", 'data-method'=>"post", 'data-confirm'=>"您确定要删除此项吗？", 'aria-label'=>Yii::t('yii', 'Delete')]
                        );
                    },
                    'listbtn' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-list"></span>', 
                            Yii::$app->urlManager->createUrl(['operation-platform-version','platform_id' => $model->id]), 
                            ['title' => Yii::t('yii', '平台版本'), 'class' => 'btn btn-warning btn-sm',]
                        );
                    },
                ],
            ],
        ],
    ]); ?>

</div>
