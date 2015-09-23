<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\FinancePopOrderLog $model
 */

$this->title = Yii::t('boss', 'Create {modelClass}', [
    'modelClass' => 'Finance Pop Order Log',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', 'Finance Pop Order Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-pop-order-log-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
