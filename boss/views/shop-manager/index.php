<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel boss\models\search\ShopManagerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Shop Managers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-manager-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div style="text-align:right">
        <?= Html::a(Yii::t('app', 'Create Shop Manager'), ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'province_id',
            'city_id',
            'county_id',
            // 'street',
            // 'principal',
            // 'tel',
            // 'other_contact',
            // 'bankcard_number',
            // 'account_person',
            // 'opening_bank',
            // 'sub_branch',
            // 'opening_address',
            // 'bl_name',
            // 'bl_type',
            // 'bl_number',
            // 'bl_person',
            // 'bl_address',
            // 'bl_create_time:datetime',
            // 'bl_photo_url:url',
            // 'bl_audit',
            // 'bl_expiry_start',
            // 'bl_expiry_end',
            // 'bl_business:ntext',
            // 'create_at',
            // 'update_at',
            // 'is_blacklist',
            // 'blacklist_time:datetime',
            // 'blacklist_cause',
            // 'audit_status',
            // 'shop_count',
            // 'worker_count',
            // 'complain_coutn',
            // 'level',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
