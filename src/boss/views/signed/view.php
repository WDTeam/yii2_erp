<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Signed */

$this->title = $model->uid;
$this->params['breadcrumbs'][] = ['label' => 'Signeds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="signed-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->uid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->uid], [
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
            'uid',
            'uname',
            'identity_number',
            'address',
            'emergency_contact',
            'shopid',
            'shopname',
            'bankcard',
            'deposit',
            'mobile',
            'contract_number',
            'contract_time:datetime',
            'signed',
            'sendSome',
        ],
    ]) ?>

</div>
