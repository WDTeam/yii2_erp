<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\FinanceHeader $model
 */

$this->title = Yii::t('boss', '{modelClass}: ', [
    'modelClass' => '配置表头',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', 'Finance Headers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('boss', 'Update');
?>
<div class="finance-header-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
