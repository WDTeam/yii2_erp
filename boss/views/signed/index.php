<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '签约阿姨';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="signed-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

<!--    <p>
        <?= Html::a('Create Signed', ['create'], ['class' => 'btn btn-success']) ?>
    </p>-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'uid',
            'uname',
            'identity_number',
            'address',
            'emergency_contact',
            // 'shopid',
            // 'shopname',
            // 'bankcard',
            // 'deposit',
            // 'mobile',
            // 'contract_number',
            // 'contract_time:datetime',
            // 'signed',
            // 'sendSome',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
