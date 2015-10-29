<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\FinanceRecordLog $model
 */

$this->title = Yii::t('boss', 'Update {modelClass}: ', [
    'modelClass' => 'Finance Record Log',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', 'Finance Record Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('boss', 'Update');
?>
<div class="finance-record-log-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
