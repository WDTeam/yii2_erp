<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use boss\models\operation\coupon\CouponRule as CouponRuleSearch;
use core\models\operation\OperationArea;


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
    		[
    		'format' => 'raw',
    		'label' => '优惠券',
    		'value' => function ($dataProvider) {
    			return \Yii::$app->redis->SRANDMEMBER($dataProvider->couponrule_Prefix);
    		},
    		],
    		[
    		'format' => 'raw',
    		'label' => '渠道名称',
    		'value' => function ($dataProvider) {
    			return $dataProvider->couponrule_channelname ==0 ?'暂无': $dataProvider->couponrule_channelname;
    		},
    		],
    		
    		
    		[
    		'format' => 'raw',
    		'label' => '类型',
    		'value' => function ($dataProvider) {
    		$configdate=CouponRuleSearch::couponconfig();
    		return $configdate[1][$dataProvider->couponrule_classify];
    		},
    		],
    		
    		[
    		'format' => 'raw',
    		'label' => '分类',
    		'value' => function ($dataProvider) {
    			$configdate=CouponRuleSearch::couponconfig();
    			return $configdate[2][$dataProvider->couponrule_category];
    		},
    		],
//            'couponrule_category_name', 
    		
    		[
    		'format' => 'raw',
    		'label' => '优惠券类别',
    		'value' => function ($dataProvider) {
    			$configdate=CouponRuleSearch::couponconfig();
    			$name_dateinfo=$configdate[3][$dataProvider->couponrule_type];
    			if($dataProvider->couponrule_type==1){
    				return $name_dateinfo;
    			}elseif($dataProvider->couponrule_type==2){
    				$data_info_name=\core\models\operation\OperationCategory::getAllCategory();
    				$data_es_name=\yii\helpers\ArrayHelper::map($data_info_name, 'id', 'operation_category_name');
    				$name=$configdate[3][$dataProvider->couponrule_type].'-'.$data_es_name[$dataProvider->couponrule_service_type_id];
    				return $name;
    			}else{
    				$goods_data=\core\models\operation\OperationGoods::getAllCategory_goods();
    				$name=$configdate[3][$dataProvider->couponrule_type].'-'.$goods_data[$dataProvider->couponrule_commodity_id];
    				return $name;
    			}
    		},
    		],
//            'couponrule_type_name', 
//            'couponrule_service_type_id', 
//            'couponrule_service_type_name', 
//            'couponrule_commodity_id', 
//            'couponrule_commodity_name', 
    		[
    		'format' => 'raw',
    		'label' => '地区限制',
    		'value' => function ($dataProvider) {
    			$configdate=CouponRuleSearch::couponconfig();
    			if($dataProvider->couponrule_city_limit==1){
    				return $configdate[4][$dataProvider->couponrule_city_limit];
    			}else{
    				$name=$configdate[4][$dataProvider->couponrule_city_limit].'-'.OperationArea::getAreaname($dataProvider->couponrule_city_id);
    				return $name;
    			}
    			
    		},
    		],
//            'couponrule_city_id', 
//            'couponrule_city_name', 

    		[
    		'format' => 'raw',
    		'label' => '适用客户类型',
    		'value' => function ($dataProvider) {
    			$configdate=CouponRuleSearch::couponconfig();
    			return $configdate[5][$dataProvider->couponrule_customer_type];
    		},
    		],
    		
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
           [
           'format' => 'raw',
           'label' => '剩余优惠券',
           'value' => function ($dataProvider) {
           	$countsum=$rt=\Yii::$app->redis->SCARD($dataProvider->couponrule_Prefix);
           	return $countsum;
           },
           ],

//            'couponrule_code_max_customer_num', 
//            'is_disabled', 
//            'created_at', 
//            'updated_at', 
//            'is_del', 
//            'system_user_id', 
//            'system_user_name', 

            [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view} {edit} {delete} {tagssign} {tagssigninfo}',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['coupon-rule/view','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('yii', 'Edit'),
                                                  ]);},
                                                  'tagssign' => function ($url, $model, $key) {
                                                  	return 
                                                  	  Html::a('导出优惠券',['operation/coupon/coupon-rule/export','id' => $model->id,'edit'=>'baksite'],['data-pjax'=>'0','class'=>'btn btn-success','target' => '_blank',]);
                                                  		
                                                  },
                                                  
                                                  'tagssigninfo' => function ($url, $model, $key) {
                                                  	return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', Yii::$app->urlManager->createUrl(['operation/coupon/coupon-userinfo/create','id' => $model->id,'edit'=>'baksite']),'');
                  }
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
