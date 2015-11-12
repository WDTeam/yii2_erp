<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use boss\models\operation\coupon\CouponUserinfo;
use boss\models\operation\coupon\CouponRule;
/**
 * @var yii\web\View $this
 * @var dbbase\models\operation\coupon\CouponUserinfo $model
 */

$this->title = $model->coupon_userinfo_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '优惠券用户'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-userinfo-view">
    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
    		'buttons1'=>'{update}',
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
            //'id',
    		[
    		'attribute' => 'customer_id',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'format'=>'raw',
    		'value'=>CouponUserinfo::get_customer_name($model->customer_id),
    		],
    		
    		
    		[
    		'attribute' => 'customer_tel',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'format'=>'raw',
    		'value'=>$model->customer_tel,
    		],
    		
    		[
    		'attribute' => 'coupon_userinfo_code',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'format'=>'raw',
    		'value'=>$model->coupon_userinfo_code,
    		],
    		[
    		'attribute' => 'coupon_userinfo_name',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'format'=>'raw',
    		'value'=>$model->coupon_userinfo_name,
    		],
    		[
    		'attribute' => 'coupon_userinfo_price',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'format'=>'raw',
    		'value'=>$model->coupon_userinfo_price,
    		],
    		
    		[
    		'attribute' => 'coupon_userinfo_gettime',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'format'=>'raw',
    		'value'=>date('Y-m-d H:i:s',$model->coupon_userinfo_gettime),
    		],
    		[
    		'attribute' => 'coupon_userinfo_usetime',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=>!empty($model->coupon_userinfo_usetime)?date('Y-m-d H:i:s',$model->coupon_userinfo_usetime):'未使用',
    		],
    		
    		[
    		'attribute' => 'couponrule_use_end_time',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'format'=>'raw',
    		'value'=>date('Y-m-d H:i:s',$model->couponrule_use_end_time),
    		],
    		[
    		'attribute' => 'couponrule_classify',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=>CouponRule::couponconfiginfo(1,$model->couponrule_classify),
    		],
    		[
    		'attribute' => 'couponrule_category',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=>CouponRule::couponconfiginfo(2,$model->couponrule_category),
    		],
    		[
    		'attribute' => 'couponrule_type',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=>CouponRule::couponconfiginfo(3,$model->couponrule_category),
    		],
    		[
    		'attribute' => 'couponrule_city_limit',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=>CouponRule::couponconfiginfo(4,$model->couponrule_city_limit),
    		],
    		[
    		'attribute' => 'couponrule_city_id',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'format'=>'raw',
    		'value'=>$model->couponrule_city_id,
    		],
    		
    		
    		[
    		'attribute' => 'couponrule_customer_type',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=>CouponRule::couponconfiginfo(5,$model->couponrule_customer_type),
    		],
    		[
    		'attribute' => 'couponrule_use_end_days',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'format'=>'raw',
    		'value'=>$model->couponrule_use_end_days,
    		],
    		
    		
    		
    		[
    		'attribute' => 'couponrule_promote_type',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=>CouponRule::couponconfiginfo(6,$model->couponrule_promote_type),
    		],
    		
    		[
    		'attribute' => 'couponrule_order_min_price',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'format'=>'raw',
    		'value'=>$model->couponrule_order_min_price,
    		],
    		
    		[
    		'attribute' => 'couponrule_price',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'format'=>'raw',
    		'value'=>$model->couponrule_price,
    		],
    		[
    		'format' => 'raw',
    		'label' => '是否禁用',
    		'attribute'=>'is_disabled',
    		'type'=> DetailView::INPUT_RADIO_LIST,
    		'items'=>['0'=>'否','1'=>'是'],
    		],
    		[
    		'attribute' => 'order_code',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'format'=>'raw',
    		'value'=>$model->order_code,
    		],
    		[
    		'attribute' => 'system_user_name',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'format'=>'raw',
    		'value'=>$model->system_user_name,
    		],
    		[
    		'attribute' => 'is_used',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=>$model->is_used==0?'否':'是',
    		],
    			
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->id],
        'data'=>[
        'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
        'method'=>'post',
        ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
