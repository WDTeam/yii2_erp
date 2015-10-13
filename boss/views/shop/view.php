<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;
use boss\models\Shop;
use boss\components\AreaCascade;
use common\components\BankHelper;

/**
 * @var yii\web\View $this
 * @var boss\models\Shop $model
 */
//\yii\base\Event::on(Shop::className(),'test', function($e){
//    var_dump($e);
//});
// $model->cause = 'fffffffffffffff';
// $model->trigger('change:blacklist');
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shops'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-view">

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
            [
                'attribute'=>'shop_manager_id',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => Select2::classname(),
                    'initValueText' => $model->getManagerName(), // set the initial display text
                    'options' => ['placeholder' => 'Search for a shop_menager ...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 0,
                        'ajax' => [
                            'url' => Url::to(['shop-manager/search-by-name']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {name:params.term}; }')
                        ],
                        //                     'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(model) { return model.name; }'),
                        'templateSelection' => new JsExpression('function (model) { return model.name; }'),
                    ],
                ],
                'value'=>$model->getManagerName(),
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
//             'province_id',
//             'city_id',
//             'county_id',
            [
                'attribute'=>'street',
            ],
            'principal',
            'tel',
            'other_contact',
            [
                'attribute'=>'worker_count',
                'type' => DetailView::INPUT_HIDDEN,
            ],
            [
                'attribute'=>'complain_coutn',
                'type' => DetailView::INPUT_HIDDEN,
            ],
            'level',
            
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
                'attribute' => 'audit_status',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'name'=>'audit_status',
                    'class'=>\kartik\widgets\Select2::className(),
                    'data' => Shop::$audit_statuses,
                    'hideSearch' => true,
                    'options'=>[
                        'placeholder' => '选择状态',
                    ]
                ],
                'format'=>'raw',
                'value'=>'<div class="col-md-2">'.Shop::$audit_statuses[(int)$model->audit_status].'</div>'.
                '<div class="col-md-6">最后审核时间:'.date('Y-m-d H:i:s', $model->getLastAuditStatus()->created_at).'</div>',
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
            'heading'=>'银行信息',
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'bankcard_number',
            [
            'attribute'=>'opening_bank',
            'type' => DetailView::INPUT_WIDGET,
            'widgetOptions' => [
                'class'=>\kartik\widgets\Select2::className(),
                'data' =>BankHelper::getBankNames(),
                'hideSearch' => false,
                'options'=>[
                    'placeholder' => '选择银行',
                ]
            ],
            ],
            'sub_branch',
            'opening_address',
            'account_person',
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
