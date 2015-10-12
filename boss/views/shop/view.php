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
//$model->trigger('test');
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
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
            [
                'attribute'=>'id',
                'type' => DetailView::INPUT_HIDDEN,
            ],
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
                ''
            ],
            'principal',
            'tel',
            'other_contact',
            
            
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
                'value'=>Shop::$is_blacklists[(int)$model->is_blacklist],
            ],
//             'blacklist_time:datetime',
//             'blacklist_cause',
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
                'value'=>Shop::$audit_statuses[(int)$model->audit_status],
            ],
            [
                'label'=>'最后审核时间',
                'format'=>'datetime',
                'value'=>date('Y-m-d H:i:s', $model->getLastAuditStatusChangeTime()),
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
            //银行信息
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
            'bankcard_number',
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
