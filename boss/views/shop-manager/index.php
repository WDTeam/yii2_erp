<?php

use yii\helpers\Html;
use yii\grid\GridView;
use boss\components\AreaCascade;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;
use boss\models\ShopManager;

/* @var $this yii\web\View */
/* @var $searchModel boss\models\search\ShopManagerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Shop Managers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-manager-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div style="text-align: right; padding-bottom:5px; clear:both">
        <div class="pull-left"><?php echo $this->render('_search', ['model' => $searchModel]); ?></div>
        <?= Html::a(Yii::t('app', 'Create Shop'), ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'id',
                'options'=>['width'=>10]
            ],
            [
                'attribute'=>'name',
                'value'=>function ($model){
                    return Yii::$app->user->createUrl('view',['id'=>$model->id]);
                }
            ],
//             'province_id',
            [
                'attribute'=>'city_id',
                'value'=>function ($model){
                    return $model->getCityName();
                },
                'filter'=>false,
            ],
//             'county_id',
            // 'street',
            'principal',
            'tel',
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
            [
                'attribute'=>'create_at',
                'value'=>function($model){
                        return date('Y-m-d', $model->create_at);
                },
                'filter'=>false,
            ],
            // 'update_at',
            // 'is_blacklist',
            // 'blacklist_time:datetime',
            // 'blacklist_cause',
            [
                'attribute'=>'audit_status',
                'options'=>['width'=>100,],
                'value'=>function($model){
                    return ShopManager::$audit_statuses[$model->audit_status];
                },
                'filter'=>ShopManager::$audit_statuses,
            ],
            [
                'attribute'=>'shop_count',
                'options'=>['width'=>50]
            ],
            [
                'attribute'=>'worker_count',
                'options'=>['width'=>50]
            ],
            [
                'attribute'=>'complain_coutn',
                'options'=>['width'=>50]
            ],
            [
            'attribute'=>'level',
            'options'=>['width'=>60]
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update} {delete}'
            ],
        ],
    ]); ?>

</div>
