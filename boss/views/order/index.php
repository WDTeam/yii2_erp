<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var core\models\order\OrderSearch $searchModel
 */

$this->title = '订单列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">
    <?php
    Modal::begin(['header' => '<h2>Hello world</h2>','toggleButton' =>['label'=>'Search','class' => 'btn btn-success hide','id'=>'modalButton'],]);
    echo $this->render('_search', ['model' => $searchModel]);
    Modal::end();
    ?>
    <?php Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'order_code',
            'order_parent_id',
            'order_is_parent',
            ['attribute'=>'created_at','content'=>function($model,$key,$index){return Html::a(date('Y:m:d H:i:s',$model->created_at),'#');}],
            'updated_at',
            'order_status_name',
            'order_service_type_name',
            'order_ip',
            'admin_id',
            'isdel',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['order/view','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('yii', 'Edit'),
                            ]);}

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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> 创建新订单', ['create'], ['class' => 'btn btn-success'])." ".
                      Html::a('<i class="glyphicon glyphicon-search"></i> 高级搜索', 'javascript:void(0)', ['class' => 'btn btn-success','onclick'=>'$("#modalButton").click();$("body").css("padding-right","0")']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>true
        ],
    ]);
    Pjax::end(); ?>

</div>
