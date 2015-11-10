<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

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
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'id',
            'couponrule_name',
            'couponrule_channelname',
            'couponrule_classify',
            'couponrule_category',
            'couponrule_category_name',
            'couponrule_type',
            'couponrule_type_name',
            'couponrule_service_type_id',
            'couponrule_service_type_name',
            'couponrule_commodity_id',
            'couponrule_commodity_name',
            'couponrule_city_limit',
            'couponrule_city_id',
            'couponrule_city_name',
            'couponrule_customer_type',
            'couponrule_customer_type_name',
            'couponrule_get_start_time:datetime',
            'couponrule_get_end_time:datetime',
            'couponrule_use_start_time:datetime',
            'couponrule_use_end_time:datetime',
            'couponrule_code',
            'couponrule_Prefix',
            'couponrule_use_end_days',
            'couponrule_promote_type',
            'couponrule_promote_type_name',
            'couponrule_order_min_price',
            'couponrule_price',
            'couponrule_price_sum',
            'couponrule_code_num',
            'couponrule_code_max_customer_num',
            'is_disabled',
            'created_at',
            'updated_at',
            'is_del',
            'system_user_id',
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
