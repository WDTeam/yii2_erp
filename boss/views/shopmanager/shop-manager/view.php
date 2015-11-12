<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use core\models\shop\ShopManager;
use boss\components\AreaCascade;
use kartik\builder\Form;

/**
 * @var yii\web\View $this
 * @var core\models\shop\ShopManager $model
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
            'heading'=>'基础信息',
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
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
                'attribute'=>'account',
                'type'=> Form::INPUT_TEXT,
            ],
            [
                'label'=>'密码',
                'attribute'=>'password',
                'type'=> Form::INPUT_PASSWORD,
            ],
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
            [
                'label'=>'添加时间',
                'value'=>date('Y-m-d H:i:s', $model->created_at),
            ],
            
//             [
//                 'attribute' => 'audit_status',
//                 'type' => DetailView::INPUT_WIDGET,
//                 'widgetOptions' => [
//                     'name'=>'worker_type',
//                     'class'=>\kartik\widgets\Select2::className(),
//                     'data' => ShopManager::$audit_statuses,
//                     'hideSearch' => true,
//                     'options'=>[
//                         'placeholder' => '选择状态',
//                     ]
//                 ],
//                 'value'=>ShopManager::$audit_statuses[$model->audit_status],
//             ],
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
            [
                'attribute' => 'is_blacklist',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => \kartik\widgets\SwitchInput::classname()
                ],
                'format'=>'raw',
                'value'=>$this->render('view_blacklist', ['model'=>$model]),
            ],
//             'level',
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
        'formOptions' =>  [
            'options' =>  ['enctype' => 'multipart/form-data']
        ],
        'condensed'=>false,
        'hover'=>true,
        'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
        'panel'=>[
            'heading'=>'营业执照',
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'bl_name',
            [
                'attribute' => 'bl_type',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class'=>\kartik\widgets\Select2::className(),
                    'data' => ShopManager::$bl_types,
                    'hideSearch' => true,
                    'options'=>[
                        'placeholder' => '选择类型',
                    ]
                ],
                'value'=>ShopManager::$bl_types[$model->bl_type],
            ],
            'bl_number',
            'bl_person',
            'bl_address',
            [
                'attribute'=>'bl_create_time',
                'type'=>DetailView::INPUT_WIDGET,
                'widgetOptions'=>[
                'class'=>DateControl::classname(),
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
                'format'=>'date',
            ],
            'bl_audit',
            [
                'attribute'=>'bl_expiry_start',
                'type'=>DetailView::INPUT_WIDGET,
                'widgetOptions'=>[
                    'class'=>DateControl::classname(),
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
                'format'=>'date',
            ],
            [
                'attribute'=>'bl_expiry_end',
                'type'=>DetailView::INPUT_WIDGET,
                'widgetOptions'=>[
                    'class'=>DateControl::classname(),
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
                'format'=>'date',
            ],
            'bl_business:ntext',
            [
                'attribute'=>'bl_photo_url',
                'type'=>DetailView::INPUT_FILE,
                'value'=>Html::img($model->getBlPhotoUrlByQiniu(),['height'=>100]),
                'format'=>'raw',
            ],
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
