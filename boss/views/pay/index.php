<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Pays');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pay-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Pay'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'customer_id',
            'order_id',
            'pay_money',
            'pay_actual_money',
            // 'pay_source',
            // 'pay_mode',
            // 'pay_status',
            // 'pay_transaction_id',
            // 'pay_eo_order_id',
            // 'pay_memo',
            // 'pay_is_coupon',
            // 'admin_id',
            // 'worker_id',
            // 'handle_admin_id',
            // 'pay_verify',
            // 'created_at',
            // 'updated_at',
            // 'is_del',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
