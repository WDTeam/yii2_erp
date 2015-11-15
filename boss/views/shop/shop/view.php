<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;
use core\models\shop\Shop;
use boss\components\AreaCascade;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var core\models\shop\Shop $model
 */


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
                            'url' => Url::to(['shopmanager/shop-manager/search-by-name']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { 
                                return {
                                    name:params.term,
                                    city_id: $("#city").val()
                                }; 
                            }')
                        ],
                        'initSelection'=> new JsExpression('function (element, callback) {
                            callback({
                                id:"'.$model->shop_manager_id.'",
                                name:"'.$model->getManagerName().'"
                            });
                        }'),
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
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
            [
                'attribute'=>'operation_shop_district_id',
                'type'=>DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'name'=>'audit_status',
                    'class'=>\kartik\widgets\Select2::className(),
                    'data' => Shop::getShopDistrictList($model->city_id),
                    'hideSearch' => false,
                    'options'=>[
                        'placeholder' => '选择商圈',
                    ]
                ],
                'value'=>$model->operation_shop_district_name,
            ],
            'principal',
            'tel',
            'other_contact',
            'level',
            [
                'label'=>'添加时间',
                'value'=>date('Y-m-d H:i:s', $model->created_at),
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
            'heading'=>'当前状态信息',
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
            [
                'attribute'=>'worker_count',
                'type' => DetailView::INPUT_HIDDEN,
                'format'=>'raw',
                'value'=>Html::a($model->worker_count,[
                    'worker/worker/index',
                    'WorkerSearch'=>[
                        'shop_id'=>$model->id
                    ],
                ]),
            ],
            [
                'attribute'=>'complain_coutn',
                'type' => DetailView::INPUT_HIDDEN,
            ],
            [
                'label' => '审核状态',
//                 'type' => DetailView::INPUT_WIDGET,
//                 'widgetOptions' => [
//                     'name'=>'audit_status',
//                     'class'=>\kartik\widgets\Select2::className(),
//                     'data' => Shop::$audit_statuses,
//                     'hideSearch' => true,
//                     'options'=>[
//                         'placeholder' => '选择状态',
//                     ]
//                 ],
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
                'data' =>$model::getBankNames(),
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
    <?php if(!Yii::$app->user->identity->isNotAdmin()){?>
    <div class="panel panel-info">
    <?php $form = ActiveForm::begin([
        'action' => ['set-audit-status', 'id'=>$model->id],
        'method' => 'post',
    ]); ?>
        <div class="panel-body row">
            <div class="col-md-3">
                <?php echo Html::activeRadioList($model, 'audit_status', Shop::$audit_statuses,[
                    'id'=>'set_audit_status'
                ]);?>
            </div>
            <div class="col-md-2">
                <?php echo Html::submitInput('确认',['class'=>'btn btn-primary']);?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
    </div>
    <?php }?>
</div>
