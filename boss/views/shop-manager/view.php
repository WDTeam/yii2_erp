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
            'id',
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
                'attribute'=>'create_at',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => DateControl::classname(),
                    'type'=>DateControl::FORMAT_DATE,
                    'ajaxConversion'=>false,
                    'displayFormat' => 'php:Y-m-d',
                    'saveFormat'=>'php:U',
                    'options' => [
                        'pluginOptions' => [
                            'autoclose' => true
                        ]
                    ]
                ],
                'value'=>date('Y-m-d H:i:s', $model->create_at),
            ],
            [
                'attribute'=>'update_at',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => DateControl::classname(),
                    'type'=>DateControl::FORMAT_DATE,
                    'ajaxConversion'=>false,
                    'displayFormat' => 'php:Y-m-d',
                    'saveFormat'=>'php:U',
                    'options' => [
                        'pluginOptions' => [
                            'autoclose' => true
                        ]
                    ]
                ],
                'value'=>date('Y-m-d H:i:s', $model->update_at),
            ],
            [
                'attribute' => 'is_blacklist',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => \kartik\widgets\SwitchInput::classname()
                ],
                'value'=>ShopManager::$is_blacklists[(int)$model->is_blacklist],
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
            'shop_count',
            'worker_count',
            'complain_coutn',
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
