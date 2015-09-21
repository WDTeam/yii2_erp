<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\FinanceRefund $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Finance Refund',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Refunds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-refund-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
