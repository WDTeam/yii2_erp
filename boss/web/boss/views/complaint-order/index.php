<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel boss\models\ComplaintOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Complaint Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="complaint-order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Complaint Order', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'order_id',
            'worker_id',
            'complaint_type',
            'complaint_section',
            // 'complaint_level',
            // 'complaint_content:ntext',
            // 'complaint_time:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
