<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\FinanceShopSettleApply $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Finance Shop Settle Apply',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Shop Settle Applies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-shop-settle-apply-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
