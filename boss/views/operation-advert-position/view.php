<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationAdvertPosition */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Operation Advert Positions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-advert-position-view">

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
            'operation_advert_position_name',
            'operation_platform_id',
            'operation_platform_name',
            'operation_platform_version_id',
            'operation_platform_version_name',
            'operation_city_id',
            'operation_city_name',
            'operation_advert_position_width',
            'operation_advert_position_height',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
