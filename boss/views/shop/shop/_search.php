<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use boss\components\AreaCascade;
use kartik\widgets\Select2;
use yii\base\Widget;
use yii\helpers\Url;
use core\models\shop\Shop;
use kartik\widgets\Affix;
use yii\web\JsExpression;

/**
 * @var yii\web\View $this
 * @var core\models\shop\ShopSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="shop-search panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 门店搜索</h3>
    </div>
    <div class="panel-body row">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>
        
        <div class="col-md-3">
            <?= $form->field($model, 'city_id')->widget(Select2::classname(), [
            'name' => 'city_id',
            'hideSearch' => true,
            'data' => $model::getOnlineCityList(),
            'options' => [
                'placeholder' => '选择城市...', 'inline' => true,
            ],
            'pluginOptions' => [
                'allowClear' => true
            ]
        ]); ?>
        </div>
        <div class="col-md-2">
            <label class="control-label" for="workersearch-worker_work_city">选择商圈</label>
            <div>
            <?php echo Select2::widget([
                'model' => $model,
                'attribute'=>'operation_shop_district_id',
                'hideSearch' => false,
                'options'=>[
                    'placeholder' => '选择商圈',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 0,
                    'ajax' => [
                        'url' => Url::to(['operation/operation-shop-district/list-to-select2']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {
                            city_id: $("#shopsearch-city_id").val(),
                            name: params.term
                        }; }')
                    ],
                    'initSelection'=> new JsExpression('function (element, callback) {
                        callback({
                            id:"'.$model->operation_shop_district_id.'",
                            operation_shop_district_name:"'.$model->getOperation_shop_district_name().'"
                        });
                    }'),
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(model) { return model.operation_shop_district_name; }'),
                    'templateSelection' => new JsExpression('function (model) { return model.operation_shop_district_name; }'),
                ],
            ]);?>
            </div>
        </div>
        <div class="col-md-2">
            <label class="control-label" for="workersearch-worker_work_city">
                                    小家政
            </label>
            <?php echo Select2::widget([
                'attribute'=>'shop_manager_id',
                'model'=>$model,
                'options' => [
                    'placeholder' => $model->getManagerName(),
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 0,
                    'ajax' => [
                        'url' => Url::to(['shopmanager/shop-manager/search-by-name']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {name:params.term}; }')
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
            ]);?>
        </div>
        <div class="col-md-3">
        <?= $form->field($model, 'name')->label('门店名称、负责人姓名、电话等') ?>
        </div>
    
        <?php // echo $form->field($model, 'county_id') ?>
    
        <?php // echo $form->field($model, 'street') ?>
    
        <?php // echo $form->field($model, 'principal') ?>
    
        <?php // echo $form->field($model, 'tel') ?>
    
        <?php // echo $form->field($model, 'other_contact') ?>
    
        <?php // echo $form->field($model, 'bankcard_number') ?>
    
        <?php // echo $form->field($model, 'account_person') ?>
    
        <?php // echo $form->field($model, 'opening_bank') ?>
    
        <?php // echo $form->field($model, 'sub_branch') ?>
    
        <?php // echo $form->field($model, 'opening_address') ?>
    
        <?php // echo $form->field($model, 'created_at') ?>
    
        <?php // echo $form->field($model, 'updated_at') ?>
    
        <?php // echo $form->field($model, 'is_blacklist') ?>
    
        <?php // echo $form->field($model, 'blacklist_time') ?>
    
        <?php // echo $form->field($model, 'blacklist_cause') ?>
    
        <?php // echo $form->field($model, 'audit_status') ?>
    
        <?php // echo $form->field($model, 'worker_count') ?>
    
        <?php // echo $form->field($model, 'complain_coutn') ?>
    
        <?php // echo $form->field($model, 'level') ?>
    
        <div class="col-md-2" style="margin-top:22px;">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>
    
        <?php ActiveForm::end(); ?>
    </div>
</div>
