<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Yii::t('operation','Operation Boot Page'));
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-boot-page-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', Yii::t('app','Add')), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
//            ['class' => 'yii\grid\CheckboxColumn'],
            'id',
            'operation_boot_page_name',
            'operation_boot_page_ios_img',
            'operation_boot_page_android_img',
            'operation_boot_page_url:url',
            // 'operation_boot_page_residence_time:datetime',
            // 'operation_boot_page_online_time:datetime',
            // 'operation_boot_page_offline_time:datetime',
            // 'created_at',
            // 'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('app', 'Operation'),
            ],
        ],
    ]); ?>

</div>
