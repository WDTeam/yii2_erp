<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model boss\models\Shop */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shops'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-view">

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
            'name',
            'shop_menager_id',
            'province_id',
            'city_id',
            'county_id',
            'street',
            'principal',
            'tel',
            'other_contact',
            'bankcard_number',
            'account_person',
            'opening_bank',
            'sub_branch',
            'opening_address',
            'create_at',
            'update_at',
            'is_blacklist',
            'blacklist_time:datetime',
            'blacklist_cause',
            'audit_status',
            'worker_count',
            'complain_coutn',
            'level',
        ],
    ]) ?>

</div>
