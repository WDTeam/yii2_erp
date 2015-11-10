<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var boss\models\Operation\OperationGoods $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Item'), 'url' => ['/operation/operation-category']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-goods-view">
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
            'operation_goods_name',
            //'operation_category_id',
//            'operation_category_ids',
            'operation_category_name',
            'operation_goods_introduction:ntext',
            'operation_goods_english_name',
//            'operation_goods_start_time',
//            'operation_goods_end_time',
//            'operation_goods_service_time_slot:ntext',
            'operation_goods_service_interval_time',
//            'operation_price_strategy_id',
//            'operation_price_strategy_name',
//            'operation_goods_price',
//            'operation_goods_balance_price',
//            'operation_goods_additional_cost',
//            'operation_goods_lowest_consume',
            'operation_goods_price_description:ntext',
//            'operation_goods_market_price',
            'operation_tags:ntext',
//            'operation_goods_app_ico:ntext',
            'created_at:datetime',
            'updated_at:datetime',
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->id],
        //'data'=>[
            ////'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
            //'method'=>'post',
        //],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
