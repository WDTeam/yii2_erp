<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Operation Advert Positions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-advert-position-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Operation Advert Position', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'operation_advert_position_name',
            'operation_platform_id',
            'operation_platform_name',
            'operation_platform_version_id',
            // 'operation_platform_version_name',
            // 'operation_city_id',
            // 'operation_city_name',
            // 'operation_advert_position_width',
            // 'operation_advert_position_height',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
