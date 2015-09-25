<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use boss\components\AreaCascade;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;
use boss\models\ShopManager;

/* @var $this yii\web\View */
/* @var $model boss\models\search\ShopManagerSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
]); ?>

<div class="col-md-3">
<?php 
echo AreaCascade::widget([
    'model' => $model,
    'options' => ['class' => 'form-control inline'],
    'label' =>'选择城市',
    'grades' => 'city',
    'is_minui'=>true,
]);
?>
</div>
<?php //echo Html::activeTextInput($model, 'is_blacklist')?>
<?php //echo Html::activeTextInput($model, 'audit_status')?>

<?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
<?php echo Html::a('待审核('.ShopManager::getAuditStatusCountByNumber(0).')',[
    'index','ShopSearch'=>['audit_status'=>0]
], [
    'class'=>'btn btn-success'
]);?>

<?php echo Html::a('未验证通过('.ShopManager::getAuditStatusCountByNumber(2).')',[
    'index','ShopSearch'=>['audit_status'=>2]
],[
    'class'=>'btn btn-success'
]);?>

<?php echo Html::a('验证通过('.ShopManager::getAuditStatusCountByNumber(1).')',[
    'index','ShopSearch'=>['audit_status'=>1]
],[
    'class'=>'btn btn-success'
]);?>

<?php echo Html::a('黑名单('.ShopManager::getIsBlacklistCount().')',[
    'index','ShopSearch[is_blacklist]'=>0
],[
    'class'=>'btn btn-success'
]);?>

<?php ActiveForm::end(); ?>
