<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use boss\components\AreaCascade;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;
use core\models\shop\Shop;

/**
 * @var yii\web\View $this
 * @var core\models\shop\ShopSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<?php 
echo Html::a('全部('.Shop::getTotal().')',[
    'index'
],[
    'class'=>'btn btn-success'
]);
echo ' ';

echo Html::a('待审核('.Shop::getAuditStatusCountByNumber(0).')',[
    'index','ShopSearch'=>['audit_status'=>0]
], [
    'class'=>'btn btn-success'
]);
echo ' ';
echo Html::a('未验证通过('.Shop::getAuditStatusCountByNumber(2).')',[
    'index','ShopSearch'=>['audit_status'=>2]
],[
    'class'=>'btn btn-success'
]);
echo ' ';
echo Html::a('验证通过('.Shop::getAuditStatusCountByNumber(1).')',[
    'index','ShopSearch'=>['audit_status'=>1]
],[
    'class'=>'btn btn-success'
]);
echo ' ';
echo Html::a('黑名单('.Shop::getIsBlacklistCount().')',[
    'index','ShopSearch[is_blacklist]'=>0
],[
    'class'=>'btn btn-success'
]);
?>

