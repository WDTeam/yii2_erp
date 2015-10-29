<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\finance\FinanceSettleApply $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Finance Settle Apply',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Settle Applies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-settle-apply-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
