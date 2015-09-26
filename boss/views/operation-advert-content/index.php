<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Operation Advert Contents';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-advert-content-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Operation Advert Content', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'operation_advert_position_name',
            'operation_city_id',
            'operation_city_name',
            'operation_advert_start_time:datetime',
            // 'operation_advert_end_time:datetime',
            // 'operation_advert_online_time:datetime',
            // 'operation_advert_offline_time:datetime',
            // 'operation_advert_picture',
            // 'operation_advert_url:url',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
