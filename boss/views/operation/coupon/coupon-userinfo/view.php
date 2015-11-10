<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

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
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'id',
            'customer_id',
            'customer_tel',
            'coupon_userinfo_id',
            'coupon_userinfo_code',
            'coupon_userinfo_name',
            'coupon_userinfo_price',
            'coupon_userinfo_gettime:datetime',
            'coupon_userinfo_usetime:datetime',
            'couponrule_use_end_time:datetime',
    		'couponrule_classify',
    		'couponrule_category',
    		'couponrule_type',
    		'couponrule_service_type_id',
    		'couponrule_commodity_id',
    		'couponrule_city_limit',
    		'couponrule_city_id',
    		'couponrule_customer_type',
    		'couponrule_use_end_days',
    		'couponrule_promote_type',
    		'couponrule_order_min_price',
    		'couponrule_price',
    		'is_disabled',
            'order_code',
            'system_user_id',
            'system_user_name',
            'is_used',
            'created_at',
            'updated_at',
            'is_del',
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
