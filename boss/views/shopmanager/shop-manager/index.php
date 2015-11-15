<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use boss\components\AreaCascade;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;
use core\models\shop\ShopManager;
use yii\bootstrap\Modal;
use yii\base\Widget;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var core\models\shop\ShopManagerSearch $searchModel
 */

$this->title = Yii::t('app', '所有家政公司');
$this->params['breadcrumbs'][] = $this->title; 

$columns[] = [
    'attribute'=>'name',
    'format'=>'raw',
    'value'=>function ($model){
        return Html::a($model->name,['view', 'id'=>$model->id]);
    }
];
$columns[] = [
    'attribute'=>'city_id',
    'value'=>function ($model){
        return $model->getCityName();
    },
    'filter'=>false,
];
$columns[] = 'principal';
$columns[] = 'tel';
if($searchModel->is_blacklist==1){
    $columns[] = [
        'label'=>'封号原因',
        'value'=>function ($model){
            return $model->getLastJoinBlackListCause();
        }
    ];
}

if($searchModel->is_blacklist==1){
    $columns[] = [
        'label'=>'被封号时间',
        'value'=>function ($model){
            return $model->getLastJoinBlackListTime();
        }
    ];
}
$columns[] = [
    'attribute'=>'created_at',
    'value'=>function($model){
        return date('Y-m-d', $model->created_at);
    },
    'filter'=>false,
];
$columns[] = [
    'attribute'=>'shop_count',
    'options'=>['width'=>70],
    'value'=>function ($model){
        return Html::a($model->shop_count, ['shop/shop/index', 'ShopSearch'=>[
            'shop_manager_id'=>$model->id
        ]]);
    },
    'format'=>'raw',
];
$columns[] = [
    'attribute'=>'worker_count',
    'options'=>['width'=>70],
];
$columns[] = [
    'attribute'=>'complain_coutn',
    'options'=>['width'=>70]
];
$columns[] = [
    'attribute'=>'level',
    'options'=>['width'=>70]
];

$columns[] = [
    'class' => 'yii\grid\ActionColumn',
    'template'=>'{update} {joinblacklist}',
    'buttons' => [
        'update' => function ($url, $model) {
            return Html::a(Yii::t('yii', '编辑'), [
                'view',
                'id' => $model->id,
                'edit'=>'t'
            ], [
                'title' => Yii::t('yii', '编辑'),
                'class' => 'btn btn-success btn-sm'
            ]);
        },
        'joinblacklist' => function ($url, $model) {
            return empty($model->is_blacklist)?Html::a('封号', [
                'join-blacklist',
                'id' => $model->id
            ], [
                'title' => Yii::t('app', '封号'),
                'data-toggle'=>'modal',
                'data-target'=>'#modal',
                'data-id'=>$model->id,
                'class'=>'join-list-btn btn btn-success btn-sm',
            ]):Html::a('解除封号', [
                'remove-blacklist',
                'id' => $model->id
                
            ], [
                'title' => Yii::t('app', '解除封号'),
                'class'=>'join-list-btn btn btn-success btn-sm',
            ]);
        },
    ],
];
?>
<div class="shop-manager-index">
    <?php if(!Yii::$app->user->identity->isNotAdmin()){
        echo $this->render('_search', ['model' => $searchModel]);
    }?>
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
//         'filterModel' => $searchModel,
        'toolbar' =>[
            'content'=>Html::a('<i class="glyphicon glyphicon-plus"></i>', [

                'create',
                
            ], [
                'class' => 'btn btn-default',
                'title' => Yii::t('app', '添加小家政')
            ]),
        ],
        'columns' => $columns,
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
    'header' => '<h4 class="modal-title">封号原因</h4>',
    'id' =>'modal',
]);?>
<?php $this->registerJs(<<<JSCONTENT
    $('.join-list-btn').click(function(){
        $('#modal .modal-body').html('加载中……');
        $('#modal .modal-body').eq(0).load(this.href);
    });
JSCONTENT
);?>
