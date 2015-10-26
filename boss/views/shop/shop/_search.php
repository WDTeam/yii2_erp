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
        
        <div class="col-md-4">
            <label class="control-label" for="workersearch-worker_work_city">所在城市</label>
            <div>
            <?php echo AreaCascade::widget([
                'model' => $model,
                'options' => ['class' => 'form-control inline'],
                'label' =>'选择城市',
                'grades' => 'city',
                'is_minui'=>true,
            ]);?>
            </div>
        </div>
        <div class="col-md-3">
            <label class="control-label" for="workersearch-worker_work_city">小家政</label>
            <?php echo Select2::widget([
                'initValueText' => $model->getManagerName(), // set the initial display text
                'attribute'=>'shop_manager_id',
                'model'=>$model,
                'options' => [],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 0,
                    'ajax' => [
                        'url' => Url::to(['shopmanager/shop-manager/search-by-name']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {name:params.term}; }')
                    ],
                    //                     'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
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
