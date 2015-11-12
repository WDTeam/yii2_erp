<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\operation\coupon\CouponUserinfo $searchModel
 */

$this->title = Yii::t('app', '优惠券用户管理');
$this->params['breadcrumbs'][] = $this->title;
?>
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
    			return $dataProvider->customer_id ==0 ?'无此人': $dataProvider->customer_id;
    		},
    		],
            'customer_tel',
            'coupon_userinfo_id',
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
//            'system_user_id', 
//            'system_user_name', 
//            'is_used', 
//            'created_at', 
//            'updated_at', 
//            'is_del', 

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
			'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> 添加绑定手机号', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                     
			'after'=>
Html::submitButton(Yii::t('app', '批量 '), ['class' => 'btn btn-default','style' => 'margin-right:10px']),

'showFooter'=>false
        ],
    ]); 

    ActiveForm::end();
    ?>

</div>
