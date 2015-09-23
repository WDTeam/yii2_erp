<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\search\ShopManagerSearch $searchModel
 */

$this->title = Yii::t('app', 'Shop Managers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-manager-index">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Shop Manager',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'province_name',
            'city_name',
            'county_name',
           'street', 
           'principal', 
           'tel', 
//            'other_contact', 
//            'bankcard_number', 
//            'account_person', 
//            'opening_bank', 
//            'sub_branch', 
//            'opening_address', 
//            'bl_name', 
//            'bl_type', 
//            'bl_number', 
//            'bl_person', 
//            'bl_address', 
//            'bl_create_time:datetime', 
//            'bl_photo_url:url', 
//            'bl_audit', 
//            'bl_expiry_start', 
//            'bl_expiry_end', 
//            'bl_business:ntext', 
           'create_at', 
//            'update_at', 
//            'is_blacklist', 
//            'blacklist_time:datetime', 
//            'blacklist_cause', 
           [
               'attribute'=>'audit_status',
               'label'=>'审核状态'
           ], 
           'shop_count', 
           'worker_count', 
//            'complain_coutn', 
           'level', 

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['shop-manager/view','id' => $model->id,'edit'=>'t']), [
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
