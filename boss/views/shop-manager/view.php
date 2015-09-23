<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var boss\models\ShopManager $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shop Managers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-manager-view">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>


    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'id',
            'name',
            'province_name',
            'city_name',
            'county_name',
            'street',
            'principal',
            'tel',
            'other_contact',
            'bankcard_number',
            'account_person',
            'opening_bank',
            'sub_branch',
            'opening_address',
            'bl_name',
            'bl_type',
            'bl_number',
            'bl_person',
            'bl_address',
            'bl_create_time:datetime',
            'bl_photo_url:url',
            'bl_audit',
            'bl_expiry_start',
            'bl_expiry_end',
            'bl_business:ntext',
            'create_at',
            'update_at',
            'is_blacklist',
            'blacklist_time:datetime',
            'blacklist_cause',
            'audit_status',
            'shop_count',
            'worker_count',
            'complain_coutn',
            'level',
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->id],
        'data'=>[
        'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
        'method'=>'post',
        ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
