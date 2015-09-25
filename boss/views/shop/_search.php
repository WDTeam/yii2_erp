<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use boss\components\AreaCascade;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;
use boss\models\Shop;

/**
 * @var yii\web\View $this
 * @var boss\models\search\ShopSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
]); ?>

<div class="col-md-4" style="padding:0">
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

<div class="col-md-1">
    <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
</div>

<?php echo Html::a('待审核('.Shop::getAuditStatusCountByNumber(0).')',[
    'index','ShopSearch'=>['audit_status'=>0]
], [
    'class'=>'btn btn-success'
]);?>

<?php echo Html::a('未验证通过('.Shop::getAuditStatusCountByNumber(2).')',[
    'index','ShopSearch'=>['audit_status'=>2]
],[
    'class'=>'btn btn-success'
]);?>

<?php echo Html::a('验证通过('.Shop::getAuditStatusCountByNumber(1).')',[
    'index','ShopSearch'=>['audit_status'=>1]
],[
    'class'=>'btn btn-success'
]);?>

<?php echo Html::a('黑名单('.Shop::getIsBlacklistCount().')',[
    'index','ShopSearch[is_blacklist]'=>0
],[
    'class'=>'btn btn-success'
]);?>


<?php ActiveForm::end(); ?>
