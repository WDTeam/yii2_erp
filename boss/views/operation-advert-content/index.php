<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Advert Content');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-advert-content-index">

    <p>
        <?= Html::a(Yii::t('app', 'Create').Yii::t('app', 'Advert Content'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'header' => Yii::t('app', 'Order Number'),
                'class' => 'yii\grid\SerialColumn'
            ],

//            'id',
            'operation_advert_position_name',
//            'operation_city_id',
//            'operation_city_name',
//            'operation_advert_start_time:datetime',
//             'operation_advert_end_time:datetime',
             'operation_advert_online_time:date',
             'operation_advert_offline_time:date',
            [
                'attribute'=> 'operation_advert_picture',
                'format'=>'html',
                'value' => function ($model){
                    if(empty($model->operation_advert_picture)){
                        return '';
                    }else{
                        return '<img src="'. $model->operation_advert_picture .'" height="30">';
                    }
               }
            ],
             'operation_advert_url:url',
             'created_at:datetime',
             'updated_at:datetime',

            [
                'header' => Yii::t('app', 'Operation'),
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open"></span>', 
                            Yii::$app->urlManager->createUrl(['operation-advert-content/view','id' => $model->id]),
                            ['title' => Yii::t('yii', 'View'), 'class' => 'btn btn-success btn-sm']
                        );
                    },
                    'update' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>', 
                            Yii::$app->urlManager->createUrl(['operation-advert-content/update','id' => $model->id]),
                            ['title' => Yii::t('yii', 'Update'), 'class' => 'btn btn-info btn-sm']
                        );
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash"></span>', 
                            Yii::$app->urlManager->createUrl(['operation-advert-content/delete','id' => $model->id]),
                            ['title' => Yii::t('yii', 'Delete'), 'class' => 'btn btn-danger btn-sm', 'data-pjax'=>"0", 'data-method'=>"post", 'data-confirm'=>"您确定要删除此项吗？", 'aria-label'=>Yii::t('yii', 'Delete')]
                        );
                    },
                ],
            ],
        ],
    ]); ?>

</div>
