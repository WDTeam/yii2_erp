<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\FinanceWorkerOrderIncome $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Finance Worker Order Income',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Worker Order Incomes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-worker-order-income-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
