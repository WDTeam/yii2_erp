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

<div class="system-user-search panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 用户搜索</h3>
    </div>
    <div class="panel-body row">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>

        <div class="col-md-3">
        <?= $form->field($model, 'username')->label('用户名、邮箱、手机号等') ?>
        </div>

        <div class="col-md-2" style="margin-top:22px;">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>
    
        <?php ActiveForm::end(); ?>
    </div>
</div>