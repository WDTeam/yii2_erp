<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model dbbase\models\OperationBootPage */

//$this->title = $model->id;
$this->title = Yii::t('app','Look').Yii::t('operation','Boot Page');
$this->params['breadcrumbs'][] = ['label' => Yii::t('operation','Operation Boot Page'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-boot-page-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app','Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app','Delete'), ['delete', 'id' => $model->id], [
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
            'operation_boot_page_name',
            'operation_boot_page_ios_img',
            'operation_boot_page_android_img',
            'operation_boot_page_url:url',
            'operation_boot_page_residence_time',
            'operation_boot_page_online_time:datetime',
            'operation_boot_page_offline_time:datetime',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
