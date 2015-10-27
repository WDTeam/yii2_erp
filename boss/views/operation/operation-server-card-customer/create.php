<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\operation\ServerCardCustomer $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Server Card Customer',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Server Card Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="server-card-customer-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
