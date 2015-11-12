<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Advert Release');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-advert-release-index">

    <p>
        <?= Html::a( Yii::t('app', 'Release Advert'), ['step-first'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'header' => Yii::t('app', 'Order Number'),
                'class' => 'yii\grid\SerialColumn'
            ],

            //'id',
            'city_name',
             'created_at:datetime',
             'updated_at:datetime',

            [
                'header' => Yii::t('app', 'Operation'),
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open"></span>', 
                            Yii::$app->urlManager->createUrl([
                                '/operation/operation-advert-release/view',
                                'city_id' => $model->city_id
                            ]),
                            [
                                'title' => Yii::t('yii', 'View'),
                                'class' => 'btn btn-success btn-sm'
                            ]
                        );
                    },
                    'update' => function(){return false;},
                    'delete' => function ($url, $model) {
                        return '';
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash"></span>', 
                            Yii::$app->urlManager->createUrl(['/operation/operation-advert-release/delete','id' => $model->id]),
                            ['title' => Yii::t('yii', 'Delete'), 'class' => 'btn btn-danger btn-sm', 'data-pjax'=>"0", 'data-method'=>"post", 'data-confirm'=>"您确定要删除此项吗？", 'aria-label'=>Yii::t('yii', 'Delete')]
                        );
                    },
                ],
            ],
        ],
    ]); ?>

</div>
