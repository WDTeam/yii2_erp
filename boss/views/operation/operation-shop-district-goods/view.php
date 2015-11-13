<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\OperationShopDistrictGoods $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Operation Shop District Goods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-shop-district-goods-view">
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
            'operation_shop_district_goods_name',
            'operation_shop_district_goods_no',
            'operation_goods_id',
            'operation_shop_district_id',
            'operation_shop_district_name',
            'operation_city_id',
            'operation_city_name',
            'operation_category_id',
            'operation_category_ids',
            'operation_category_name',
            'operation_shop_district_goods_introduction:ntext',
            'operation_shop_district_goods_english_name',
            'operation_shop_district_goods_start_time',
            'operation_shop_district_goods_end_time',
            'operation_shop_district_goods_service_time_slot:ntext',
            'operation_shop_district_goods_service_interval_time:datetime',
            'operation_shop_district_goods_service_estimate_time:datetime',
            'operation_spec_info',
            'operation_spec_strategy_unit',
            'operation_shop_district_goods_price',
            'operation_shop_district_goods_balance_price',
            'operation_shop_district_goods_additional_cost',
            'operation_shop_district_goods_lowest_consume_num',
            'operation_shop_district_goods_lowest_consume',
            'operation_shop_district_goods_price_description:ntext',
            'operation_shop_district_goods_market_price',
            'operation_tags:ntext',
            'operation_goods_img:ntext',
            'operation_shop_district_goods_app_ico:ntext',
            'operation_shop_district_goods_pc_ico:ntext',
            'operation_shop_district_goods_status',
            'is_softdel',
            'created_at',
            'updated_at',
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
