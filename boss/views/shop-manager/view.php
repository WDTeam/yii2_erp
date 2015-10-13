<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use boss\models\ShopManager;
use boss\components\AreaCascade;
use kartik\builder\Form;

/**
 * @var yii\web\View $this
 * @var boss\models\ShopManager $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shop Managers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-manager-view">

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
            [
                'attribute'=>'id',
                'type'=>DetailView::INPUT_HIDDEN,
            ],
            'name',
//             [
//                 'type'=>DetailView::INPUT_WIDGET,
//                 'widgetOptions' => [
//                     'class'=>AreaCascade::className(),
//                     'model' => $model,
//                     'attribute'=>'city_id',
//                     'options' => ['class' => 'form-control inline'],
//                     'label' =>'选择城市',
//                     'grades' => 'county',
//                 ],
//             ],
            [
                'attribute'=>'city_id',
                'label'=>'地址',
                'value'=>$model->getAllAddress(),
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions'=>[
                    'class'=>AreaCascade::className(),
                    'model' => $model,
                    'options' => ['class' => 'form-control inline'],
                    'label' =>'选择城市',
                    'grades' => 'county',
                    'is_minui'=>true,
                ],
            ],
            'street',
            'principal',
            'tel',
            'other_contact',
            'bl_name',
            [
                'attribute' => 'bl_type',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'name'=>'worker_type',
                    'class'=>\kartik\widgets\Select2::className(),
                    'data' => ShopManager::$bl_types,
                    'hideSearch' => true,
                    'options'=>[
                        'placeholder' => '选择类型',
                    ]
                ],
                'value'=>ShopManager::$bl_types[$model->bl_type],
            ],
            [
                'attribute'=>'created_at',
                'type' => DetailView::INPUT_HIDDEN,
                'value'=>date('Y-m-d H:i:s', $model->created_at),
            ],
            [
                'attribute'=>'updated_at',
                'type' => DetailView::INPUT_HIDDEN,
                'value'=>date('Y-m-d H:i:s', $model->updated_at),
            ],
            [
                'attribute' => 'is_blacklist',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => \kartik\widgets\SwitchInput::classname()
                ],
                'format'=>'raw',
                'value'=>$this->render('view_blacklist', ['model'=>$model]),
            ],
//             'blacklist_time:datetime',
//             'blacklist_cause',
            [
                'attribute' => 'audit_status',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'name'=>'worker_type',
                    'class'=>\kartik\widgets\Select2::className(),
                    'data' => ShopManager::$audit_statuses,
                    'hideSearch' => true,
                    'options'=>[
                        'placeholder' => '选择状态',
                    ]
                ],
                'value'=>ShopManager::$audit_statuses[$model->audit_status],
            ],
            [
                'attribute'=>'shop_count',
                'type' => DetailView::INPUT_HIDDEN,
            ],
            [
                'attribute'=>'worker_count',
                'type' => DetailView::INPUT_HIDDEN,
            ],
            [
                'attribute'=>'complain_coutn',
                'type' => DetailView::INPUT_HIDDEN,
            ],
            'level',
            
            
            'bl_number',
            'bl_person',
            'bl_address',
            'bl_create_time:datetime',
            [
                'attribute'=>'bl_photo_url',
                'type'=>DetailView::INPUT_FILE,
                'value'=>Html::img($model->getBlPhotoUrlByQiniu(),['height'=>100]),
                'format'=>'raw',
            ],
            'bl_audit',
            'bl_expiry_start:datetime',
            'bl_expiry_end:datetime',
            'bl_business:ntext',
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
            [
                'attribute'=>'id',
                'type'=>DetailView::INPUT_HIDDEN,
            ],
            'name',
//             [
//                 'type'=>DetailView::INPUT_WIDGET,
//                 'widgetOptions' => [
//                     'class'=>AreaCascade::className(),
//                     'model' => $model,
//                     'attribute'=>'city_id',
//                     'options' => ['class' => 'form-control inline'],
//                     'label' =>'选择城市',
//                     'grades' => 'county',
//                 ],
//             ],
            [
                'attribute'=>'city_id',
                'label'=>'地址',
                'value'=>$model->getAllAddress(),
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions'=>[
                    'class'=>AreaCascade::className(),
                    'model' => $model,
                    'options' => ['class' => 'form-control inline'],
                    'label' =>'选择城市',
                    'grades' => 'county',
                    'is_minui'=>true,
                ],
            ],
            'street',
            'principal',
            'tel',
            'other_contact',
            'bl_name',
            [
                'attribute' => 'bl_type',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'name'=>'worker_type',
                    'class'=>\kartik\widgets\Select2::className(),
                    'data' => ShopManager::$bl_types,
                    'hideSearch' => true,
                    'options'=>[
                        'placeholder' => '选择类型',
                    ]
                ],
                'value'=>ShopManager::$bl_types[$model->bl_type],
            ],
            [
                'attribute'=>'created_at',
                'type' => DetailView::INPUT_HIDDEN,
                'value'=>date('Y-m-d H:i:s', $model->created_at),
            ],
            [
                'attribute'=>'updated_at',
                'type' => DetailView::INPUT_HIDDEN,
                'value'=>date('Y-m-d H:i:s', $model->updated_at),
            ],
            [
                'attribute' => 'is_blacklist',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => \kartik\widgets\SwitchInput::classname()
                ],
                'value'=>ShopManager::$is_blacklists[(int)$model->is_blacklist],
            ],
            [
                'label'=>'加入黑名单原因',
                'value'=>$model->getLastJoinBlackListCause()
            ],
//             'blacklist_time:datetime',
//             'blacklist_cause',
            [
                'attribute' => 'audit_status',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'name'=>'worker_type',
                    'class'=>\kartik\widgets\Select2::className(),
                    'data' => ShopManager::$audit_statuses,
                    'hideSearch' => true,
                    'options'=>[
                        'placeholder' => '选择状态',
                    ]
                ],
                'value'=>ShopManager::$audit_statuses[$model->audit_status],
            ],
            [
                'attribute'=>'shop_count',
                'type' => DetailView::INPUT_HIDDEN,
            ],
            [
                'attribute'=>'worker_count',
                'type' => DetailView::INPUT_HIDDEN,
            ],
            [
                'attribute'=>'complain_coutn',
                'type' => DetailView::INPUT_HIDDEN,
            ],
            'level',
            
            
            'bl_number',
            'bl_person',
            'bl_address',
            'bl_create_time:datetime',
            [
                'attribute'=>'bl_photo_url',
                'type'=>DetailView::INPUT_FILE,
                'value'=>Html::img($model->getBlPhotoUrlByQiniu(),['height'=>100]),
                'format'=>'raw',
            ],
            'bl_audit',
            'bl_expiry_start:datetime',
            'bl_expiry_end:datetime',
            'bl_business:ntext',
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
