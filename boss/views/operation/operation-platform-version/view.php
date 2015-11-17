<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationPlatformVersion */

$this->title = $this->title = Yii::t('app', 'Look').Yii::t('app', 'Platform').Yii::t('app', 'Version');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Platform'), 'url' => ['/operation/operation-platform/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Platform').Yii::t('app' ,'Version'), 'url' => ['index', 'platform_id' => $platform_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-platform-version-view">

    <!--<h1><?php //= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id, 'platform_id' => $platform_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id, 'platform_id' => $platform_id], [
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
            'operation_platform_name',
            'operation_platform_version_name',
            ['attribute' => 'created_at', 'value' => empty($model->created_at)? '' : date('Y-m-d H:i:s', $model->created_at)],
            ['attribute' => 'updated_at', 'value' => empty($model->updated_at)? '' : date('Y-m-d H:i:s', $model->updated_at)],
        ],
    ]) ?>

</div>
