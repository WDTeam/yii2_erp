<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\Customer $model
 */

$this->title = Yii::t('boss', 'Create {modelClass}', [
    'modelClass' => 'Customer',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', 'Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
