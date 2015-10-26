<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use \core\models\customer\Customer;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\CustomerCommentSearch $searchModel
 */

$this->title = Yii::t('boss', '评论管理');
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-comment-index">
    <div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 评论搜索</h3>
    </div>
    <div class="panel-body">
        <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    </div>
  
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'export'=>false,
        'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
        'headerRowOptions'=>['class'=>'kartik-sheet-style'],
        'filterRowOptions'=>['class'=>'kartik-sheet-style'],
        'toolbar' =>
            [
                'content'=>'',
            ],
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
            ],
            // ['class' => 'yii\grid\SerialColumn'],
    		'created_at',
    		'city_id',
    		'operation_shop_district_id',
    		'customer_comment_level',
    		'customer_comment_tag_names',
    		'customer_comment_content',
    		'order_id',
    		'customer_id',
    		'worker_id',
    		/* 
    		
            'order_id',
            [
                'format' => 'raw',
                'label' => '客户名称',
                'value' => function ($dataProvider) {
                    $customer = Customer::findOne($dataProvider->customer_id);
                    return $customer == NULL ? '-' : $customer->customer_name;
                },
                'width' => "80px",
            ],
            [
                'format' => 'raw',
                'label' => '客户手机',
                'value' => function ($dataProvider) {
                    return $dataProvider->customer_comment_phone;
                },
                'width' => "80px",
            ],
            'customer_comment_content',
            'customer_comment_star_rate', 
            [
                'format' => 'raw',
                'label' => '身份',
                'value' => function ($dataProvider) {
                    return $dataProvider->customer_comment_anonymous ? '匿名' : '非匿名';
                },
                'width' => "80px",
            ],
            [
                'format' => 'datetime',
                'label' => '创建时间',
                'value' => function ($dataProvider) {
                    return $dataProvider->created_at;
                },
                'width' => "120px",
            ], */
           // 'updated_at', 
           // 'is_del', 
            // [
            //     'class' => 'yii\grid\ActionColumn',
            //     'buttons' => [
            //     'update' => function ($url, $model) {
            //         return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['customer-comment/view','id' => $model->id,'edit'=>'t']), [
            //             'title' => Yii::t('yii', 'Edit'),
            //         ]);}
            //     ],
            // ],
        ],
        'responsive' => true,
        'hover' => true,
        'condensed' => true,
        'floatHeader' => true,
        'striped'=>false,
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> ' . Html::encode($this->title) . ' </h3>',
            'type' => 'info',
            'before' =>'',
            'after' =>'',
            'showFooter' => false
        ],
    ]);
    ?>
</div>
