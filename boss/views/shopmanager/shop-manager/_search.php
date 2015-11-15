<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use boss\components\AreaCascade;
use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var core\models\shop\ShopManagerSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 小家政搜索</h3>
        </div>
        <div class="panel-body">
            <div class="shop-manager-search row">

            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
            ]); ?>
        
            <div class="col-md-4">
                <?= $form->field($model, 'city_id')->widget(Select2::classname(), [
                    'name' => 'city_id',
                    'hideSearch' => true,
                    'data' => $model::getOnlineCityList(),
                    'options' => ['placeholder' => '选择城市...', 'inline' => true],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
            
            <div class="col-md-3">
            <?= $form->field($model, 'name')->label('小家政名称、负责人姓名、电话等') ?>
            </div>
        
            <div class="col-md-2" style="margin-top:22px;">
                <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
                <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
            </div>
        
            <?php ActiveForm::end(); ?>
        
        </div>
    </div>
</div>

