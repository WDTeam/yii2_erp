<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\coupon\Coupon $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', 'Coupons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-view">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>


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
            'coupon_name',
            'coupon_price',
            'coupon_type',
            'coupon_type_name',
            'coupon_service_type_id',
            'coupon_service_type_name',
            'coupon_service_id',
            'coupon_service_name',
            'coupon_city_limit',
            'coupon_city_id',
            'coupon_city_name',
            'coupon_customer_type',
            'coupon_customer_type_name',
            'coupon_time_type:datetime',
            'coupon_time_type_name',
            'coupon_begin_at',
            'coupon_end_at',
            'coupon_get_end_at',
            'coupon_use_end_days',
            'coupon_promote_type',
            'coupon_promote_type_name',
            'coupon_order_min_price',
            'coupon_code_num',
            'coupon_code_max_customer_num',
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
