<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

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
   

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'customer_id',
            'customer_tel',
            'coupon_userinfo_id',
            'coupon_userinfo_code',
            'coupon_userinfo_name', 
            'coupon_userinfo_price', 
            'coupon_userinfo_gettime:datetime', 
            'coupon_userinfo_usetime:datetime', 
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
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['coupon-userinfo/view','id' => $model->id,'edit'=>'t']), [
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
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
