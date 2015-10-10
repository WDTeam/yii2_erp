<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use boss\components\AreaCascade;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;
use boss\models\ShopManager;
use yii\bootstrap\Modal;
use yii\base\Widget;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\search\ShopManagerSearch $searchModel
 */

$this->title = Yii::t('app', '所有家政公司');
$this->params['breadcrumbs'][] = $this->title; 
?>
<div class="shop-manager-index">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 小家政搜索</h3>
        </div>
        <div class="panel-body">
            <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
//         'filterModel' => $searchModel,
        'toolbar' =>[
            'content'=>Html::a('<i class="glyphicon glyphicon-plus"></i>', [
                'shop-manager/create'
            ], [
                'class' => 'btn btn-default',
                'title' => Yii::t('app', '添加小家政')
            ]),
        ],
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            
//             [
//                 'attribute'=>'id',
//                 'options'=>['width'=>10]
//             ],
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
            //             'county_id',
            // 'street',
            'principal',
            'tel',
            [
                'attribute'=>'created_at',
                'value'=>function($model){
                    return date('Y-m-d', $model->created_at);
                },
                'filter'=>false,
            ],
//            [
//                'attribute'=>'audit_status',
//                'options'=>['width'=>100,],
//                'value'=>function($model){
//                    return ShopManager::$audit_statuses[$model->audit_status];
//                },
//                'filter'=>ShopManager::$audit_statuses,
//            ],
            [
                'attribute'=>'shop_count',
                'options'=>['width'=>70],
                'value'=>function ($model){
                    return Html::a($model->shop_count, ['shop/index', 'ShopSearch'=>[
                        'shop_manager_id'=>$model->id
                    ]]);
                },
                'format'=>'raw',
            ],
            [
                'attribute'=>'worker_count',
                'options'=>['width'=>70],
//                 'value'=>function ($model){
//                     return Html::a($model->worker_count, ['worker/index', 'ShopSearch'=>[
//                         'shop_manager_id'=>$model->id
//                     ]]);
//                 },
//                 'format'=>'raw',
            ],
            [
                'attribute'=>'complain_coutn',
                'options'=>['width'=>70]
            ],
            [
                'attribute'=>'level',
                'options'=>['width'=>70]
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update} {joinblacklist}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', [
                            'shop-manager/view',
                            'id' => $model->id,
                            'edit'=>'t'
                        ], [
                            'title' => Yii::t('yii', 'Edit'),
                        ]);
                    },
                    'joinblacklist' => function ($url, $model) {
                        return empty($model->is_blacklist)?Html::a('加入黑名单', [
                            'shop-manager/join-blacklist',
                            'id' => $model->id
                        ], [
                            'title' => Yii::t('app', '加入黑名单'),
                            'data-toggle'=>'modal',
                            'data-target'=>'#modal',
                            'data-id'=>$model->id,
                            'class'=>'join-list-btn',
                        ]):Html::a('解除黑名单', [
                            'shop-manager/remove-blacklist',
                            'id' => $model->id
                            
                        ], [
                            'title' => Yii::t('app', '解除黑名单'),
                        ]);
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
            'before' =>$this->render('_index_links', ['model' => $searchModel]),
//             'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List',
//                 ['index'],
//                 ['class' => 'btn btn-info']),
                'after'=>false,
            'showFooter' => false
        ],
    ]); Pjax::end(); ?>
</div>
<?php echo Modal::widget([
    'header' => '<h4 class="modal-title">黑名单原因</h4>',
    'id' =>'modal',
]);?>
<?php $this->registerJs(<<<JSCONTENT
    $('.join-list-btn').click(function(){
        $('#modal .modal-body').html('加载中……');
        $('#modal .modal-body').eq(0).load(this.href);
    });
JSCONTENT
);?>
