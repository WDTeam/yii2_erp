<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use core\models\shop\Shop;
use boss\components\AreaCascade;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\bootstrap\Modal;
use yii\base\Widget;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var core\models\shop\ShopSearch $searchModel
 */

$this->title = Yii::t('app', '所有门店');
$this->params['breadcrumbs'][] = $this->title;

$columns = [];
$columns[] = ['class' => 'yii\grid\SerialColumn'];
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
if($searchModel->is_blacklist==0){
    $columns[] = 'principal';
}
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

if($searchModel->is_blacklist==0){
    $columns[] = [
        'attribute'=>'created_at',
        'value'=>function($model){
                return date('Y-m-d', $model->created_at);
        },
        'filter'=>false,
    ];
    $columns[] = [
        'attribute'=>'audit_status',
        'options'=>['width'=>100,],
        'value'=>function($model){
            return Shop::$audit_statuses[$model->audit_status];
        },
        'filter'=>Shop::$audit_statuses,
    ];
}
$columns[] = [
    'attribute'=>'shop_manager_id',
    'format'=>'raw',
    'value'=>function ($model){
        return Html::a($model->getManagerName(), [
            'shopmanager/shop-manager/view', 
            'id'=>$model->shop_manager_id]);
    },
    'options'=>['width'=>200,],
    ];
$columns[] = [
    'attribute'=>'worker_count',
    'format'=>'raw',
    'value'=>function ($model){
        return Html::a($model->worker_count,[
            'worker/worker/index',
            'WorkerSearch'=>[
                'shop_id'=>$model->id
            ],
        ]);  
    },
];
$columns[] = 'complain_coutn';
$columns[] = 'level';
$columns[] = [
    'class' => 'yii\grid\ActionColumn',
    'template'=>Yii::$app->user->identity->isNotAdmin()?
        '{update} {add-worker}':
        '{update} {delete} {joinblacklist} {add-worker}',
    'buttons' => [
        'update' => function ($url, $model) {
            return Html::a(Yii::t('yii', '编辑'), ['view', 'id' => $model->id, 'edit' => 't'], [
                'title' => Yii::t('yii', '编辑'),
                'class' => 'btn btn-success btn-sm'
            ]);
        },
        'delete' => function ($url, $model) {
            return Html::a(
                Yii::t('yii', 'Delete'),
                ['delete','id' => $model->id, 'id'=> $model->id],
                ['title' => Yii::t('yii', 'Delete'), 'class' => 'btn btn-danger btn-sm', 'data-pjax'=>"0", 'data-method'=>"post", 'data-confirm'=>"您确定要删除此项吗？", 'aria-label'=>Yii::t('yii', 'Delete')]
            );
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
                'id' => $model->id,
            ], [
                'title' => Yii::t('app', '解除封号'),
                'class'=>'join-list-btn btn btn-success btn-sm',
            ]);
        },
        'add-worker' => function ($url, $model) {
        return Html::a('录入新阿姨', [
                'worker/worker/create',
                'shop_id' => $model->id,
                'city_id'=>$model->city_id,
            ], [
                'title' => Yii::t('app', '录入新阿姨'),
                'class'=>'add-worker-btn btn btn-success btn-sm',
            ]);
        },
    ],
];

?>
<div class="shop-index">

    <?php  
    $is_admin = !Yii::$app->user->identity->isNotAdmin();
    if($is_admin){
        echo $this->render('_search', ['model' => $searchModel]);
    }
    ?>
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
//         'filterModel' => $searchModel,
        'toolbar' =>[
            'content'=>Html::a('<i class="glyphicon glyphicon-plus"></i>', [
            'create'
            ], [
                'class' => 'btn btn-default',
                'title' => Yii::t('app', '添加新门店')
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
            'before' =>$is_admin?$this->render('_index_links', ['model' => $searchModel]):false,
            'after'=>false,
            'showFooter'=>false
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
