<?php
use yii\helpers\Html;
use kartik\detail\DetailView;
use boss\models\operation\coupon\CouponRule;

/**
 * @var yii\web\View $this
 * @var dbbase\models\operation\coupon\CouponRule $model
 */

$this->title = $model->couponrule_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '优惠券管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-rule-view">
  
    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
    		'buttons1'=>'{delete}',
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
            //'id',
            'couponrule_name',
    		[
    		'attribute' => 'couponrule_channelname',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'format'=>'raw',
    		'value'=>$model->couponrule_channelname?$model->couponrule_channelname:'暂无',
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
            'couponrule_category_name',
             [
    		'attribute' => 'couponrule_type',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=>CouponRule::couponconfiginfo(3,$model->couponrule_category),
    		],
            'couponrule_type_name',
            'couponrule_service_type_id',
            'couponrule_service_type_name',
            'couponrule_commodity_id',
            'couponrule_commodity_name',
    		
    		[
    		'attribute' => 'couponrule_city_limit',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=>CouponRule::couponconfiginfo(4,$model->couponrule_city_limit),
    		],
            'couponrule_city_id',
            'couponrule_city_name',
    		[
    		'attribute' => 'couponrule_customer_type',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=>CouponRule::couponconfiginfo(5,$model->couponrule_customer_type),
    		],
            'couponrule_customer_type_name',
            'couponrule_get_start_time:datetime',
            'couponrule_get_end_time:datetime',
            'couponrule_use_start_time:datetime',
            'couponrule_use_end_time:datetime',
    		[
    		'attribute' => 'couponrule_code',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=>CouponRule::getcouponcode($model->couponrule_Prefix,50),
    		],
            'couponrule_Prefix',
            'couponrule_use_end_days',
    		[
    		'attribute' => 'couponrule_promote_type',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=>CouponRule::couponconfiginfo(6,$model->couponrule_promote_type),
    		],
            'couponrule_promote_type_name',
            'couponrule_order_min_price',
            'couponrule_price',
            'couponrule_price_sum',
            'couponrule_code_num',
            'couponrule_code_max_customer_num',
    		[
    		'attribute' => 'is_disabled',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=>$model->is_disabled==0?'否':'是',
    		],
            'created_at:datetime',
            'updated_at:datetime',
            'system_user_name',
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
