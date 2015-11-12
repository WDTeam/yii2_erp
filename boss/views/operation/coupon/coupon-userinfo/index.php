<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\ActiveForm;
use boss\models\operation\coupon\CouponRule;
use boss\models\operation\coupon\CouponUserinfo;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\operation\coupon\CouponUserinfo $searchModel
 */

$this->title = Yii::t('app', '优惠券用户管理');
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    textarea[name="export_content"] {display: none !important;}
</style>
<div class="coupon-userinfo-index">
   
   <div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i>优惠券用户搜索</h3>
    </div>
    <div class="panel-body">
        <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>
   

    <?php 
    ActiveForm::begin([
    'action' => ['indexall'],
    'method' => 'post'
    		]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
            'columns' => [
            [
     		'class' => 'yii\grid\CheckboxColumn',
     		'name'=>'ids'
			],

            //'id',
    		[
    		'format' => 'raw',
    		'label' => '客户名称',
    		'value' => function ($dataProvider) {
    			return CouponUserinfo::get_customer_name($dataProvider->customer_id);
    		},
    		],
            'customer_tel',
            [
            'format' => 'raw',
            'label' => '分类',
            'value' => function ($dataProvider) {
            	return CouponRule::couponconfiginfo(2,$dataProvider->couponrule_category);
            },
            ],
            [
            'format' => 'raw',
            'label' => '分类',
            'value' => function ($dataProvider) {
            	return CouponRule::couponconfiginfo(4,$dataProvider->couponrule_city_limit);
            },
            ],
            
            
            'coupon_userinfo_code',
            'coupon_userinfo_name', 
            'coupon_userinfo_price', 
            'coupon_userinfo_gettime:datetime', 
            [
            'format' => 'raw',
            'label' => '使用时间',
            'value' => function ($dataProvider) {
            	$msg=!empty($dataProvider->coupon_userinfo_usetime)?date('Y-m-d H:i:s',$dataProvider->coupon_userinfo_usetime):'未使用';
            	return $msg;
            },
            ],
            
            'couponrule_use_end_time:datetime', 
            'order_code', 
            [
            'format' => 'raw',
            'label' => '状态',
            'value' => function ($dataProvider) {
            	return $dataProvider->is_disabled ==0 ?'启用':'禁用';
            },
            ],
            [
            'format' => 'raw',
            'label' => '使用状态',
            'value' => function ($dataProvider) {
            	return $dataProvider->is_used ==0 ?'未使用':'已使用';
            },
            ],
            
            
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['operation/coupon/coupon-userinfo/view','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('yii', '修改'),
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
			'after'=>
Html::submitButton(Yii::t('app', '批量 '), ['class' => 'btn btn-default','style' => 'margin-right:10px']),

'showFooter'=>false
        ],
    ]); 

    ActiveForm::end();
    ?>

</div>
