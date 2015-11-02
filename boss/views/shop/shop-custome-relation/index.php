<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use core\models\system\SystemUser;
use core\models\shop\Shop;
use core\models\shop\ShopManager;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\shop\ShopCustomeRelationSearch $searchModel
 */

$this->title = Yii::t('app', '用户关系列表');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-custome-relation-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
    		[
    		'format' => 'raw',
    		'label' => '用户名',
    		'value' => function ($dataProvider) {
    			return  SystemUser::get_id_name($dataProvider->system_user_id);
    		},
    		],
            [
            'format' => 'raw',
            'label' => '家政公司',
            'value' => function ($dataProvider) {
            	return ShopManager::get_id_name($dataProvider->shop_manager_id);
            },
            
            ],
            [
            'format' => 'raw',
            'label' => '门店',
            'value' => function ($dataProvider) {
            	return Shop::get_id_name($dataProvider->shopid);
            },
            ],
            [
            'format' => 'raw',
            'label' => '类型',
            'value' => function ($dataProvider) {
            	return $dataProvider->stype==1?'家政公司':'门店';
    },
            ],
//            'is_del', 

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['shop-custome-relation/view','id' => $model->id,'edit'=>'t']), [
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> 添加', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
