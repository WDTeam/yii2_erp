<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationPlatform */

$this->title = Yii::t('app', 'Look').Yii::t('app', 'Platform');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Platform'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-platform-view">

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
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
            'operation_platform_name',
            ['attribute' => 'created_at', 'value' => empty($model->created_at)? '' : date('Y-m-d H:i:s', $model->created_at)],
            ['attribute' => 'updated_at', 'value' => empty($model->updated_at)? '' : date('Y-m-d H:i:s', $model->updated_at)],
        ],
    ]) ?>

</div>
