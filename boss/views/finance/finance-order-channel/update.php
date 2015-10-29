<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\FinanceOrderChannel $model
 */

$this->title = Yii::t('boss', 'Update {modelClass}: ', [
    'modelClass' => 'Finance Order Channel',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', 'Finance Order Channels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('boss', 'Update');
?>
<div class="finance-order-channel-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
