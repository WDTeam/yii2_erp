<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\FinanceInvoice $model
 */

$this->title = 'Update Finance Invoice: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Finance Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="finance-invoice-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
