<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Advert Release');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-advert-release-index">

    <p>
        <?= Html::a( Yii::t('app', 'Release Advert'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'header' => Yii::t('app', 'Order Number'),
                'class' => 'yii\grid\SerialColumn'
            ],

//            'id',
            'operation_city_name',
            'operation_platform_name',
            'operation_platform_version_name',
            
//            'operation_advert_position_id',
            'operation_advert_position_name:ntext',
//            'operation_advert_content_id',
            'operation_advert_content_name',
//             'created_at:datetime',
//             'updated_at:datetime',

            [
                'header' => Yii::t('app', 'Operation'),'class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
