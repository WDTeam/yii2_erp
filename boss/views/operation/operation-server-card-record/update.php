<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\operation\OperationServerCardRecord $model
 */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Operation Server Card Record',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Server Card Records'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<style>
	.btn-success,
	.btn.btn-primary,
	.btn.btn-default{
		background-color: #f6a202 !important;
		color: #fff !important;
		border: none !important;
	}
</style>
<div class="operation-server-card-record-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
