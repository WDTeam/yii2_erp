<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\operation\ServerCardCustomer $model
 */

$this->title = Yii::t('app', '修改客户服务卡: ', [
    'modelClass' => 'Server Card Customer',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '修改客户服务卡'), 'url' => ['index']];
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

<div class="server-card-customer-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
