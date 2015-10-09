<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationAdvertPosition */

$this->title = Yii::t('app', 'Look').Yii::t('app', 'Advert Position');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Advert Position'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Look');
?>
<div class="operation-advert-position-view">

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a(Yii::t('app', 'List'), ['index'], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'operation_advert_position_name',
//            'operation_platform_id',
            'operation_platform_name',
//            'operation_platform_version_id',
            'operation_platform_version_name',
//            'operation_city_id',
//            'operation_city_name',
            'operation_advert_position_width',
            'operation_advert_position_height',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
