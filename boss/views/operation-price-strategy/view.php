<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\OperationPriceStrategy */

$this->title = '价格策略详情';
$this->params['breadcrumbs'][] = ['label' => '价格策略', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-price-strategy-view">

<!--    <h1><?php //= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'operation_price_strategy_name',
            'operation_price_strategy_unit',
            'operation_price_strategy_lowest_consume_unit',
            ['attribute' => 'created_at', 'value' => empty($model->created_at)? '' : date('Y-m-d H:i:s', $model->created_at)],
            ['attribute' => 'updated_at', 'value' => empty($model->updated_at)? '' : date('Y-m-d H:i:s', $model->updated_at)],
        ],
    ]) ?>

</div>
