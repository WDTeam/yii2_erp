<?php

use yii\helpers\Html;
use yii\grid\GridView;
use boss\components\AreaCascade;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;
use core\models\shop\ShopManager;

/* @var $this yii\web\View */
/* @var $searchModel core\models\shop\ShopManagerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Shop Managers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-manager-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="row">
        <div class="col-md-10"><?php echo $this->render('_search', ['model' => $searchModel]); ?></div>
        <div class="col-md-2 text-right"><?= Html::a(Yii::t('app', 'Create Shop'), ['create'], ['class' => 'btn btn-success']) ?></div>
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
                'format'=>'raw',
                'value'=>function ($model){
                    return Html::a($model->name,['view', 'id'=>$model->id]);
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
            [
                'attribute'=>'created_at',
                'value'=>function($model){
                        return date('Y-m-d', $model->created_at);
                },
                'filter'=>false,
            ],
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
