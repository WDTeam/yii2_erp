<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\operation\coupon\CouponRule $searchModel
 */

$this->title = Yii::t('app', '优惠券规则管理');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-rule-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',
            'couponrule_name',
            'couponrule_channelname',
            'couponrule_classify',
            'couponrule_category',
//            'couponrule_category_name', 
           'couponrule_type', 
//            'couponrule_type_name', 
//            'couponrule_service_type_id', 
//            'couponrule_service_type_name', 
//            'couponrule_commodity_id', 
//            'couponrule_commodity_name', 
            'couponrule_city_limit', 
//            'couponrule_city_id', 
//            'couponrule_city_name', 
            'couponrule_customer_type', 
//            'couponrule_customer_type_name', 
//            'couponrule_get_start_time:datetime', 
//            'couponrule_get_end_time:datetime', 
            'couponrule_use_start_time:datetime', 
            'couponrule_use_end_time:datetime', 
//            'couponrule_code', 
//            'couponrule_Prefix', 
//            'couponrule_use_end_days', 
//            'couponrule_promote_type', 
//            'couponrule_promote_type_name', 
//            'couponrule_order_min_price', 
            'couponrule_price', 
            'couponrule_price_sum', 
           'couponrule_code_num', 
//            'couponrule_code_max_customer_num', 
//            'is_disabled', 
//            'created_at', 
//            'updated_at', 
//            'is_del', 
//            'system_user_id', 
//            'system_user_name', 

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['coupon-rule/view','id' => $model->id,'edit'=>'t']), [
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
