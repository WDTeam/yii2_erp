<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\FinanceInvoice $model
 */

$this->title = 'Create Finance Invoice';
$this->params['breadcrumbs'][] = ['label' => 'Finance Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-invoice-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
