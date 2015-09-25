<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use boss\components\AreaCascade;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

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

<?php 
echo AreaCascade::widget([
    'model' => $model,
    'options' => ['class' => 'form-control inline'],
    'label' =>'选择城市',
    'grades' => 'city',
    'is_minui'=>true,
]);
?>
<?php //echo Html::activeTextInput($model, 'is_blacklist')?>
<?php //echo Html::activeTextInput($model, 'audit_status')?>

<?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end(); ?>
