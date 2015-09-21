<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model boss\models\OperationCategoryType */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Operation Category Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-category-type-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'operation_category_type_name',
            'operation_category_id',
            'operation_category_name',
            'operation_category_type_introduction:ntext',
            'operation_category_type_english_name',
            'operation_category_type_start_time',
            'operation_category_type_end_time',
            'operation_category_type_service_time_slot:ntext',
            'operation_category_type_service_interval_time:datetime',
            'operation_price_strategy_id',
            'operation_price_strategy_name',
            'operation_category_type_price',
            'operation_category_type_balance_price',
            'operation_category_type_additional_cost',
            'operation_category_type_lowest_consume',
            'operation_category_type_price_description:ntext',
            'operation_category_type_market_price',
            'operation_tags:ntext',
            'operation_category_type_app_ico:ntext',
            'operation_category_type_pc_ico:ntext',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
