<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationAdvertRelease */

$this->title = Yii::t('app', 'Look').Yii::t('app', 'Advert Release');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Advert Release'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-advert-release-view">

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
//            'operation_city_name',
            'operation_platform_name',
            'operation_platform_version_name',
//            'operation_advert_position_id',
            'operation_advert_position_name',
//            'operation_advert_content_id',
            'operation_advert_contents',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
