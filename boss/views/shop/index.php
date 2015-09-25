<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use boss\models\Shop;
use boss\components\AreaCascade;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\search\ShopSearch $searchModel
 */

$this->title = Yii::t('app', 'Shops');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
        
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
//         'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'name',
                'format'=>'raw',
                'value'=>function ($model){
                    return Html::a($model->name,['view', 'id'=>$model->id]);
                }
            ],
            
//             'province_id',
            [
                'attribute'=>'city_id',
                'value'=>function ($model){
                    return $model->getCityName();
                },
                'filter'=>false,
            ],
            // 'county_id',
            // 'street',
            'principal',
            'tel',
            // 'other_contact',
            // 'bankcard_number',
            // 'account_person',
            // 'opening_bank',
            // 'sub_branch',
            // 'opening_address',
            [
                'attribute'=>'create_at',
                'value'=>function($model){
                        return date('Y-m-d', $model->create_at);
                },
                'filter'=>false,
            ],
            // 'update_at',
            // 'is_blacklist',
            // 'blacklist_time:datetime',
            // 'blacklist_cause',
            [
                'attribute'=>'audit_status',
                'options'=>['width'=>100,],
                'value'=>function($model){
                    return Shop::$audit_statuses[$model->audit_status];
                },
                'filter'=>Shop::$audit_statuses,
            ],
            [
                'attribute'=>'shop_menager_id',
                'value'=>function ($model){
                    return $model->getMenagerName();
                },
                'options'=>['width'=>200,],
                'filter'=>Select2::widget([
                    'initValueText' => '', // set the initial display text
                    'model'=>$searchModel,
                    'attribute'=>'shop_menager_id',
                    'options'=>[
                        
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        
                        'minimumInputLength' => 0,
                        'ajax' => [
                            'url' => Url::to(['shop-manager/search-by-name']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {name:params.term}; }')
                        ],
                        //                     'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(model) { return model.name; }'),
                        'templateSelection' => new JsExpression('function (model) { return model.name; }'),
                    ],
                ])
            ],
            // 'worker_count',
            // 'complain_coutn',
            // 'level',

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['shop/view', 'id' => $model->id, 'edit' => 't']), [
                            'title' => Yii::t('yii', 'Edit'),
                        ]);
                    }
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
            'before' =>$this->render('_index_links', ['model' => $searchModel]),
            'after'=>false,
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
